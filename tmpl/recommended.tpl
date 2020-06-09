<?php foreach ($recommendeds as $recommended) { ?>
	<div class="block">
		<div class="header"><?=$recommended->header?></div>
		<div class="content">
			<div class="free">
				<p class="title"><?=$recommended->sub_header?></p>
				<a rel="external" href="<?=$recommended->link?>">
					<img src="/<?=$recommended->img?>" alt="<?=$recommended->title?>" />
				</a>
				<?=$recommended->text?>
				<?php if ($recommended->did) { ?>
					<form name="free_recommended" action="http://oz.by/sseries/more150670.html" method="post" onsubmit="return SR_submit(this)">
						<p class="center">
									<input type="hidden" value="1" name="version">
									<input type="hidden" value="187271" name="tid">
									<input type="hidden" value="85884" name="uid">
									<input type="hidden" value="ru" name="lang">
									<input type="hidden" value="<?=$recommended->did?>" name="did[]">
									<input type="submit" name="free_recommended" value="Узнать больше" class="button" formtarget="_blank"/>
						</p>
					</form>
				<?php } else { ?>
					<div class="center">
						<a href="<?=$recommended->link?>" class="button" target="_blank">Подробнее</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>