<?php

class DbHelper
{
	private static $_conn = null;
	private static $_dsn = "mysql:dbname=sklad;host=localhost";
	private static $_username = "root";
	private static $_password = "";
	private static $_options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

	public static function getConnection()
	{
		if (self::$_conn == null) {
			try {
				self::$_conn = new PDO(self::$_dsn, self::$_username, self::$_password, self::$_options);
			} catch (PDOException $e) {
				var_dump($e);
				die();
				echo "Възникна грешка: " . $e->getMessage();
			}
		}
		return self::$_conn;
	}

	public function postUser($username, $password, $email, $telephone)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('INSERT INTO users(username, password, email, telephone) VALUES(?, ?, ?, ?)');
			$stm->execute(array(
				$username,
				$password,
				$email,
				$telephone
			));
			echo "Effectet rows: " . $stm->rowCount();
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function findUser($username, $password)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('SELECT * FROM users WHERE username = ?');
			$stm->execute(array($username));
			$count =  $stm->rowCount();
			$user = $stm->fetch(PDO::FETCH_ASSOC);
			if ($count > 0) {
				$verify = password_verify($password, $user["password"]);
				if ($verify)
					return $user["id"];
				else return -1;
			} else return -1;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function findUserById($id)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('SELECT * FROM users WHERE id = ?');
			$stm->execute(array($id));
			$user = $stm->fetch(PDO::FETCH_ASSOC);
			return $user;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function checkProductCode($code)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('SELECT * FROM products WHERE code = ?');
			$stm->execute(array($code));
			$count =  $stm->rowCount();
			if ($count > 0) return true;
			else return false;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}
	public function checkProductCodeWithId($code, $id)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('SELECT * FROM products WHERE code = ? AND id NOT IN (?)');
			$stm->execute(array($code, $id));
			$count =  $stm->rowCount();
			if ($count > 0) return true;
			else return false;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function postProduct($title, $description, $sale_price, $pruchase_price, $count, $category, $code, $imgContent)
	{
		$conn = $this->getConnection();
		try {
			$stm = $conn->prepare('INSERT INTO products(title, description, sale_price, purchase_price, count, category, code, image) VALUES(?, ?, ?, ?, ?, ?, ?, ?)');
			$stm->execute(array(
				$title,
				$description,
				$sale_price,
				$pruchase_price,
				$count,
				$category,
				$code,
				$imgContent
			));
			$efectedRows =  $stm->rowCount();
			if ($efectedRows > 0) return true;
			else return false;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function putProduct($id, $title, $description, $sale_price, $pruchase_price, $count, $category, $code, $imgContent)
	{
		$conn = $this->getConnection();
		try {
			$stm =
				$conn->prepare('UPDATE products SET title = ?, description = ?, sale_price = ?, purchase_price = ?, count = ?, category = ?, code =?, image=? WHERE id=?');
			$stm->execute(array(
				$title,
				$description,
				$sale_price,
				$pruchase_price,
				$count,
				$category,
				$code,
				$imgContent,
				$id
			));
			$efectedRows =  $stm->rowCount();
			if ($efectedRows > 0) return true;
			else return false;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function getTotalNumberOfProducts($title, $category, $code)
	{
		$title = "%" . $title . "%";
		$category = "%" . $category . "%";
		$code = "%" . $code . "%";
		$conn = $this->getConnection();
		try {
			if (!$code) {
				$stm = $conn->prepare('SELECT * FROM products WHERE title LIKE :title AND category LIKE :category');
				$stm->bindParam(':title', $title);
				$stm->bindParam(':category', $category);
				$stm->execute();
				$number_of_results =  $stm->rowCount();
				return $number_of_results;
			} else {
				$stm = $conn->prepare('SELECT * FROM products WHERE code LIKE :code');
				$stm->bindParam(':code', $code);
				$stm->execute();
				$number_of_results =  $stm->rowCount();
				return $number_of_results;
			}
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}
	public function getAllProducts($title, $category, $code, $page, $results_per_page)
	{
		$title = "%" . $title . "%";
		$category = "%" . $category . "%";
		$code = "%" . $code . "%";
		$page = ($page - 1) * $results_per_page;


		$conn = $this->getConnection();
		try {

			if ($code === "%%") {
				$stm = $conn->prepare('SELECT * FROM products WHERE title LIKE :title AND category LIKE :category LIMIT :l OFFSET :o');
				$stm->bindParam(':title', $title);
				$stm->bindParam(':category', $category);
				$stm->bindParam(':l', $results_per_page, PDO::PARAM_INT);
				$stm->bindParam(':o', $page, PDO::PARAM_INT);
				$stm->execute();
				$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
				return $rows;
			} else {
				$stm = $conn->prepare('SELECT * FROM products WHERE code LIKE :code LIMIT :l OFFSET :o');
				$stm->bindParam(':code', $code);
				$stm->bindParam(':l', $results_per_page, PDO::PARAM_INT);
				$stm->bindParam(':o', $page, PDO::PARAM_INT);
				$stm->execute();
				$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
				return $rows;
			}
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function deleteProduct($id)
	{
		try {
			$conn = $this->getConnection();
			$stm = $conn->prepare('DELETE FROM products WHERE id = ?');
			$stm->execute(array($id));
			$efectedRows =  $stm->rowCount();
			if ($efectedRows > 0) return true;
			else return false;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}

	public function getProductByID($id)
	{
		try {
			$conn = $this->getConnection();
			$stm = $conn->prepare('SELECT * FROM products WHERE id = ?');
			$stm->execute(array($id));
			$product = $stm->fetch(PDO::FETCH_ASSOC);
			return $product;
		} catch (PDOException $e) {
			echo "Възникна грешка при записа: " . $e->getMessage();
		}
	}
}
