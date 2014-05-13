jQuery.noConflict();

jQuery(document).ready(function($) {
    $.tablesorter.addParser({
	id: 'go-ranks',
	is: function(s) {
		return /[1-9][0-9]*[dk]/.test(s.toLowerCase());
	},
	format: function(s) {
		var x;
		switch (s.toLowerCase()) {
			case "9d": x = 0; break;
			case "8d": x = 1; break;
			case "7d": x = 2; break;
			case "6d": x = 3; break;
			case "5d": x = 4; break;
			case "4d": x = 5; break;
			case "3d": x = 6; break;
			case "2d": x = 7; break;
			case "1d": x = 8; break;
			case "1k": x = 9; break;
			case "2k": x = 10; break;
			case "3k": x = 11; break;
			case "4k": x = 12; break;
			case "5k": x = 13; break;
			case "6k": x = 14; break;
			case "7k": x = 15; break;
			case "8k": x = 16; break;
			case "9k": x = 17; break;
			case "10k": x = 18; break;
			case "11k": x = 19; break;
			case "12k": x = 20; break;
			case "13k": x = 21; break;
			case "14k": x = 22; break;
			case "15k": x = 23; break;
			case "16k": x = 24; break;
			case "17k": x = 25; break;
			case "18k": x = 26; break;
			case "19k": x = 27; break;
			case "20k": x = 28; break;
			case "21k": x = 29; break;
			case "22k": x = 30; break;
			case "23k": x = 31; break;
			case "24k": x = 32; break;
			case "25k": x = 33; break;
			case "26k": x = 34; break;
			case "27k": x = 35; break;
			case "28k": x = 36; break;
			case "29k": x = 37; break;
			case "30k": x = 38; break;
		}
		//console.log(s + ' = ' + x);
		return x;
	},
	type: 'numeric'
    })
});
