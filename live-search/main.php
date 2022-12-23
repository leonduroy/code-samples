/****************** Серверная часть *******************/
/* Выполняется в том случае, если передан POST-запрос */

<?php
	if (isset($_POST['name'])) {
		$word = $_POST['name'];

		$connect = new PDO('mysql:host=localhost;dbname=localhost_db', 'root', '', [
			PDO::ATTR_PERSISTENT => true
		]);
		
		// Запрос возвращает первые 10 товаров, соответствующих искомому тексту,
		// количество которых на складе более двух.

		$result = $connect->query("
			SELECT gd.name
				FROM goods AS gd
				JOIN stock AS st ON st.good_id = gd.id
				WHERE gd.name REGEXP '^$name' 
				GROUP BY gd.name
				HAVING sum(st.quantity) >= 3
				ORDER BY gd.name
				LIMIT 10
		");
?>
	<ul>
		<?php foreach ($result as $item): ?>
			<li>
				<a href="<?= $item['name'] ?>"><?= $item['name'] ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php
		exit;
	}
?>

/*************** Клиентская часть ***************/
/* Выполняется, если POST-запрос не передавался */

<form id="search">
	<input type="text" name="name">
</form>

<div id="live_search"></div>

<script>
	// Событие нажатия клавиши в строке поиска, при котором на сервер передается название товара для выборки.
	// Возвращаемый результат отображается в виде HTML-разметки.

	document.querySelector('#search').addEventListener('keyup', () => {
		fetch('/main.php', {
			method: 'POST',
			body: new FormData(document.querySelector('#search'))
		})
			.then(response => response.text())
				.then(result => document.querySelector('#live_search').innerHTML = result);
	});
</script>
