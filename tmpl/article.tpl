<?php function printComment($comment, &$comments, $childrens, $auth_user) { ?>
	<div class="comment" id="comment_<?=$comment->id?>">
		<img src="<?=$comment->user->avatar?>" alt="<?=$comment->user->name?>" />
		<span class="name"><?=$comment->user->name?></span>
		<span class="date"><?=$comment->date?></span>
		<p class="text"><?=$comment->text?></p>
		<div class="clear"></div>
		<p class="functions"><span <?php if (!$auth_user) { ?>onclick="alert('Для добавления комментариев необходимо авторизоваться на сайте!')"<?php } else { ?>class="reply_comment"<?php } ?>>Ответить</span>
			<?php if ($auth_user) { ?><?php if ($comment->accessEdit($auth_user, "text")) { ?><span class="edit_comment">Редактировать</span> <?php } if ($comment->accessDelete($auth_user)) { ?><span class="delete_comment">Удалить</span><?php } ?><?php } ?>
		</p>
		<?php
			while (true) {
				$key = array_search($comment->id, $childrens);
				if (!$key) break;
				unset($childrens[$key]);
		?>
			<?php if (isset($comments[$key])) { ?>
				<?=printComment($comments[$key], $comments, $childrens, $auth_user)?>
			<?php } ?>
		<?php } ?>
	</div>
<?php } ?>
<div class="main">
	<?php if (isset($hornav)) { ?><?=$hornav?><?php } ?>
	<article>
		<h1><?=$article->title?></h1>
		<?php if ($article->img) { ?>
			<div class="article_img">
				<img src="<?=$article->img?>" alt="<?=$article->title?>" />
			</div>
		<?php } ?>
		<?=$article->full?>
		<div class="article_info">
			<ul>
				<li>
					<div>
						<img src="/images/date_article.png" alt="" />
					</div>
					Создано <?=$article->date?>
				</li>
				<li>
					<img src="/images/icon_user.png" alt="" />
					Андрей Фиц
				</li>
			</ul>
			<div class="clear"></div>
		</div>
	</article>
	<div id="article_pn">
		<?php if ($prev_article) { ?><a id="prev_article" href="<?=$prev_article->link?>">Предыдущая книга</a><?php } ?>
		<?php if ($next_article) { ?><a id="next_article" href="<?=$next_article->link?>">Следующая книга</a><?php } ?>
		<div class="clear"></div>
	</div>
	<div id="article_copy">
		<p class="center"><i>Копирование материалов разрешается только с указанием автора (Андрей Фиц) и индексируемой прямой ссылкой на сайт (<a href="<?=Config::ADDRESS?>"><?=Config::ADDRESS?></a>)!</i></p>
	</div>
	<div id="article_vk">
		<p>Добавляйтесь ко мне в друзья <b>ВКонтакте</b>: <a rel="external" href="https://vk.com/andreyfits">https://vk.com/andreyfits</a>.</p>
	</div>
	<p>Если у Вас остались какие-либо вопросы, либо у Вас есть желание высказаться по поводу этой статьи, то Вы можете оставить свой комментарий внизу страницы.</p>
	<div id="share">
		<p>Порекомендуйте эту статью друзьям:</p>
		<script type="text/javascript">getSocialNetwork("<?=Config::DIR_IMG?>", "");</script>
	</div>
	<p>Если Вам понравился сайт, то разместите ссылку на него (у себя на сайте, на форуме, в контакте):</p>
	<div id="comments">
		<h2 class="h1">Комментарии (<span id="count_comments"><?=count($comments)?></span>):</h2>
		<input type="button" value="Добавить комментарий" id="add_comment" <?php if (!$auth_user) { ?>onclick="alert('Для добавления комментариев необходимо авторизоваться на сайте!')"<?php } ?> />
		<?php foreach ($comments as $comment) { ?>
			<?php if ($comment->parent_id == 0) { ?><?=printComment($comment, $comments, $childrens, $auth_user)?><?php } ?>
		<?php } ?>
		<div class="clear"></div>
		<?php if ($auth_user) { ?>
			<div id="form_add_comment">
				<form name="form_add_comment" method="post" action="#">
					<div id="comment_cancel">
						<span>X</span>
					</div>
					<table>
						<tr>
							<td>
								<label for="text_comment">Комментарий:</label>
							</td>
							<td>
								<textarea cols="40" rows="5" name="text_comment" id="text_comment"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="2>
								<input type="hidden" value="0" name="comment_id" id="comment_id" />
								<input type="hidden" value="<?=$article->id?>" name="article_id" id="article_id" />
								<input type="hidden" value="0" name="parent_id" id="parent_id" />
								<input type="button" value="Сохранить" class="button" />
							</td>
						</tr>
					</table>
				</form>
			</div>
		<?php } else { ?>
			<p class="center">Для добавления комментариев надо войти в систему.<br />Если Вы ещё не зарегистрированы на сайте, то сначала <a href="<?=$link_register?>">зарегистрируйтесь</a>.</p>
		<?php } ?>
	</div>
</div>