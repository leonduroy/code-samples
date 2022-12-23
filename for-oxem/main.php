<?php

	/******************* Класс Farm *******************/

	abstract class Farm {
		private static $allAnimals = array();
		private static $allProducts = array();

		public static function addNewAnimal($animal) {
			array_push(self::$allAnimals, $animal);
		}

		public static function displayAnimalQuantity() {
			$animalTypes = array();

			foreach (self::$allAnimals as $animal) {
				if (isset($animalTypes[$animal->getAnimalType()]))
					$animalTypes[$animal->getAnimalType()]++;
				else
					$animalTypes[$animal->getAnimalType()] = 1;
			}

			echo '<b>Поголовье скота.</b><br>';

			foreach ($animalTypes as $type => $quantity)
				echo $type . ': ' . $quantity . '.<br>';

			echo '<br>';
		}

		public static function collectProducts() {
			foreach (self::$allAnimals as $animal) {
				$productType = $animal->getProductType();
				$productQuantity = $animal->collectProduction();

				if (isset(self::$allProducts[$productType]))
					self::$allProducts[$productType] += $productQuantity;
				else
					self::$allProducts[$productType] = $productQuantity;
			}
		}

		public static function displayProductQuantity() {
			echo '<b>Количество собранной продукции.</b><br>';

			foreach (self::$allProducts as $productType => $productQuantity)
				echo $productType . ': ' . $productQuantity . '.<br>';

			echo '<br>';
		}

		public static function toEmptyProducts() {
			self::$allProducts = array();
		}
	}

	/******************* Класс Animal *******************/

	abstract class Animal {
		private $id;
		private static $quantity = 1;
		
		function __construct() {
			$this->id = $this->getAnimalType() . self::$quantity++;
		}

		public function getId() {
			return $this->$id;
		}

		abstract public function getAnimalType();
		abstract public function getProductType();
		abstract public function collectProduction();
	}

	class Cow extends Animal {
		public function getAnimalType() {
			return 'Коровы';
		}

		public function getProductType() {
			return 'Молоко';
		}

		public function collectProduction() {
			return rand(8, 12);
		}
	}

	class Chicken extends Animal {
		public function getAnimalType() {
			return 'Куры';
		}

		public function getProductType() {
			return 'Яйца';
		}

		public function collectProduction() {
			return rand(0, 1);
		}
	}

	/******************* Скрипт *******************/
	
	for ($i = 0; $i < 10; $i++)
		Farm::addNewAnimal(new Cow());		// Добавляем на ферму 10 коров.

	for ($i = 0; $i < 20; $i++)
		Farm::addNewAnimal(new Chicken());	// Добавляем на ферму 20 кур.

	Farm::displayAnimalQuantity();			// Выводим информацию о количестве животных.

	for ($i = 0; $i < 7; $i++)
		Farm::collectProducts();			// Семь 'дней' производим сбор продукции.
	
	Farm::displayProductQuantity();			// Выводим на экран количество собранной продукции.

	Farm::toEmptyProducts();				// Очистка продукции.

	Farm::addNewAnimal(new Cow());			// Добавляем на ферму еще одну корову.

	for ($i = 0; $i < 5; $i++)
		Farm::addNewAnimal(new Chicken());	// Добавляем на ферму еще 5 куриц.

	Farm::displayAnimalQuantity();			// Снова выводим информацию о количестве животных.

	for ($i = 0; $i < 7; $i++)
		Farm::collectProducts();			// Снова семь 'дней' производим сбор продукции.
	
	Farm::displayProductQuantity();			// Снова выводим на экран количество собранной продукции.
