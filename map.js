let get = location.search;
let getParams = new URLSearchParams(get);

function toParse (x) {
	return (!isNaN(parseFloat(x)) ? parseFloat(x) : 0);
}

// Доступные проекции и соответствующие значения эксцентриситетов.
var projections = [{
		name: 'wgs84Mercator',
		eccentricity: 0.0818191908426
	}, {
		name: 'sphericalMercator',
		eccentricity: 0
	}], 
	
	// Для вычисления номера нужного тайла следует задать параметры:
	// - уровень масштабирования карты;
	// - географические координаты объекта, попадающего в тайл;
	// - проекцию, для которой нужно получить тайл.
	params = {
		z: toParse(getParams.get("z")),
		geoCoords: [toParse(getParams.get("x")),
		toParse(getParams.get("y"))],
		projection: projections[0]
   };
   
// Функция для перевода географических координат объекта 
// в глобальные пиксельные координаты.	
function fromGeoToPixels (lat, long, projection, z) {
	var x_p, y_p,
		pixelCoords,
		tilenumber = [],
		rho,
		pi = Math.PI,
		beta,   
		phi,
		theta,
		e = projection.eccentricity;
 
	rho = Math.pow(2, z + 8) / 2;
	beta = lat * pi / 180;
	phi = (1 - e * Math.sin(beta)) / (1 + e * Math.sin(beta));
	theta = Math.tan(pi / 4 + beta / 2) * Math.pow(phi, e / 2);
	
	x_p = rho * (1 + long / 180);
	y_p = rho * (1 - Math.log(theta) / pi);
	
	return [x_p, y_p];
}

// Функция для расчета номера тайла на основе глобальных пиксельных координат.
function fromPixelsToTileNumber (x, y) {
	return [
		Math.floor(x / 256),
		Math.floor(y / 256)
	];
}

// Переведем географические координаты объекта в глобальные пиксельные координаты.
pixelCoords = fromGeoToPixels(
	params.geoCoords[0],
	params.geoCoords[1],
	params.projection,
	params.z
);

// Посчитаем номер тайла на основе пиксельных координат.
tileNumber = fromPixelsToTileNumber(pixelCoords[0], pixelCoords[1]);

// Отобразим результат.
document.addEventListener("DOMContentLoaded", function () {
	document.getElementById("form").innerHTML = '<form>' +
	'<p>Широта:</p><input type="text" name="x" value="' + params.geoCoords[0] + '"><br><br>' +
	'<p>Долгота:</p><input type="text" name="y" value="' + params.geoCoords[1] + '"><br><br>' +
	'<p>Зум:</p><input type="text" name="z" value="' + params.z + '"><br><br>' +
	'<input type="submit" value="OK"></form>';

	document.getElementById("result").innerHTML = "<p><b>Результат вычислений:</b></p>" +
	"<p>Номер тайла: [" + tileNumber[0] + ", " + tileNumber[1] + "]</p>";

	document.getElementById("map").innerHTML = '<img alt="По заданным координатам парковок нет"' +
	'src="https://core-carparks-renderer-lots.maps.yandex.net/maps-rdr-carparks/tiles?l=carparks' +
	'&x=' + tileNumber[0] +
	'&y=' + tileNumber[1] +
	'&z=' + params.z +
	'&scale=1&lang=ru_RU">';
});
