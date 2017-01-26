<div class="infoList">
<?php
$args=array(
            'post_type'		=> 'post',
            'posts_per_page'=> $list,       // リスト数を指定
			'category__not_in'	=> 1, 		// カテゴリが未分類の記事は非表示
            'category_name'	=> $catname,    // カテゴリー名（スラッグ）を指定
        ); ?>
<?php $the_query = new WP_Query($args); ?>

<?php if($the_query->have_posts()): while($the_query->have_posts()):
$the_query->the_post(); ?>

  <?php get_template_part( 'module_loop_post2' ); ?>

<?php endwhile; endif; ?>

  <?php wp_reset_postdata(); ?>
</div>
