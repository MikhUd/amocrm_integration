<?php


echo <<<HTML

		<style>
			* {
				background-color: #ffffff;
			}
		</style>

		<form action="ApiFetcher.php" method="get">
            <div><input type="text" placeholder="Дата начала периода" name="date_start"></div>
            <div><input type="text" placeholder="Дата конца периода" name="date_finish"></div>
            <div><input type="submit" value="Отправить"></div>
        </form>

HTML;





