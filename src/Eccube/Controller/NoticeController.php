<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Eccube\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
define ( 'WP_USE_THEMES', false );
require_once (WORDPRESS_PATH . "/wp-blog-header.php");
class NoticeController extends AbstractController {
	
	/**
	 * NoticeController constructor.
	 *
	 * @param PurchaseFlow $cartPurchaseFlow        	
	 * @param CustomerFavoriteProductRepository $customerFavoriteProductRepository        	
	 * @param CartService $cartService        	
	 * @param ProductRepository $productRepository        	
	 * @param BaseInfoRepository $baseInfoRepository        	
	 * @param AuthenticationUtils $helper        	
	 * @param ProductListMaxRepository $productListMaxRepository        	
	 */
	public function __construct() {
	}
	
	/**
	 * ご利用ガイド.
	 *
	 * @Route("/notice/lists", name="notice_lists" ,methods={"GET"})
	 * @Template("notice/lists.twig")
	 */
	public function lists(Request $request) {
		$size = $request->get ( "size" );
		$index = $request->get ( "index" );
    	if($size==null){
    		$size=10;
    	}
    	if($index==null){
    		$index=1;
    	}
		 $args= [
				'posts_per_page' => $size,
				 'paged'=>$index
				 ];
   
		$list=query_posts ( $args );
		 //log_info('list:',['list' => $list] );
		$posts=array();
		foreach ( $list as $el ) {
			//$url=$this->get_image_url($el->post_content);
			$url=get_the_post_thumbnail_url($el->ID);
             log_info('thumb_url:',['thumb_url' => $thumb_url] );
			/*
			if($url!=null&&sizeof($url)>0){
				$url=$url[0];
				$pieces =explode("=", $url);
				$url=$pieces[1];
				$url=str_replace('"', '', $url);
			}else{
				$url="/html/template/default/assets/img/default-thumb.jpg";
			}*/
			if($url==null){
				$url="/html/template/default/assets/img/default-thumb.jpg";
			}
			$abstract=$this->getAbstract($el->post_content);
			$postAndImage=[
					'post'=>$el,
					'image'=>$url,
					'abstract'=>$abstract
			];
			array_push($posts, $postAndImage);
			
		}
		$total=5;
		$statistic =wp_count_posts($type = 'post');
		$rowCount=$statistic->publish;
		//计算页数
		$pageCount=$rowCount/$size;
		if($rowCount%$size>0){
			$pageCount=$pageCount+1;
		}
	    
		return [
				'lists' => $posts,
				'index' => $index,
				'size' => $size,
				'pageCount'=>$pageCount
		];

	}
	
	/**
	 * 博客文章.
	 *
	 * @Route("/notice/detail/{id}", name="notice_detail", methods={"GET"}, requirements={"id" = "\d+"})
	 * @Template("notice/detail.twig")
	 *
	 * @param Request $request        	
	 *
	 *
	 * @return array
	 */
	public function detail(Request $request) {
		$id = $request->get ( "id" );
		$args = array (
				'ID' => $id 
		);
		$post = get_post ( $id );
		$url = rawurlencode ( 'https://offisu-soft.com/notice/detail/' . $id );
		$title = rawurlencode ( $post->post_title );
		$titleAndURL = rawurlencode ( $post->post_title . 'https://offisu-soft.com/notice/detail/' . $id );
		$postMeta = get_post_meta ( $id );
		log_info('postMeta', ['postMeta' => $postMeta]);
		$description = $postMeta ['_aioseop_description'] [0] == null ? $postMeta ['description'] [0] : $postMeta ['_aioseop_description'] [0];
		$keyword= $postMeta ['_aioseo_keywords'] [0] ;
		$outLine =  $post->post_content ;

		//$outLine = $this->add_outline ( $post->post_content );
		$recentPostArgs = array(
		'numberposts' => 7, 
        'post_status' => 'publish' 
			
		);

		$recent_posts=wp_get_recent_posts($recentPostArgs);
		$thumb_url=get_the_post_thumbnail_url($id);
             log_info('thumb_url:',['thumb_url' => $thumb_url] );
		/*
		log_info('recent_posts:',['posts' => $recent_posts] );
		$final_posts=array();
		foreach ( $recent_posts as $ele ) {
		
		  $imgUrl=$this->get_image_url($ele['post_content']);
		  //
		
           if($imgUrl!=null&&sizeof($imgUrl)>0){
				$imgUrl=$imgUrl[0];
				$pieces =explode("=", $imgUrl);
				$imgUrl=$pieces[1];
				$imgUrl=str_replace('"', '', $imgUrl);
			}else{
				$imgUrl="/html/template/default/assets/img/default-thumb.jpg";
			}

		  //
          $thumb_urls=[
					'thumb_url'=>$imgUrl,	
			];
		     
			 $ele['thumb_url']=$imgUrl;
			 array_push($final_posts, $ele);
		    
		}
       log_info('recent_posts:',['posts' => $final_posts] );
	   */
	   // 获取热门文章

	 
		return [ 
				'description' => $description,
			    'keyword'=>$keyword,
				// 'subtitle' =>'22',
				'post' => $post,
				'url' => $url,
				'title' => $title,
				'titleAndURL' => $titleAndURL,
				'outLine' => $outLine ,
			    'recentPosts'=>$recent_posts,
			    'thumb_url'=>$thumb_url
		];
	}
	
	private function getAbstract($content){
		$abstract=strip_tags($content);
		$abstract=substr($abstract,0,400);
		return $abstract;
	}
	
	private function get_image_url($content){
		preg_match("%src=\"([^\"])+(png|jpg|gif)\"%i",$content,$result);
		return $result;
	}
	private function get_outline_info($content) {
		// 目次のHTMLを入れる変数を定義します。
		$outline = '';
		// h1〜h6タグの個数を入れる変数を定義します。
		$counter = 0;
		// 記事内のh1〜h6タグを検索します。(idやclass属性も含むように改良)
		if (preg_match_all ( '/<h([1-6])[^>]*>(.*?)<\/h\1>/', $content, $matches, PREG_SET_ORDER )) {
			// 記事内で使われているh1〜h6タグの中の、1〜6の中の一番小さな数字を取得します。
			// ※以降ソースの中にある、levelという単語は1〜6のことを表します。
			$min_level = min ( array_map ( function ($m) {
				return $m [1];
			}, $matches ) );
			// スタート時のlevelを決定します。
			// ※このレベルが上がる毎に、<ul></li>タグが追加されていきます。
			$current_level = $min_level - 1;
			// 各レベルの出現数を格納する配列を定義します。
			$sub_levels = array (
					'1' => 0,
					'2' => 0,
					'3' => 0,
					'4' => 0,
					'5' => 0,
					'6' => 0 
			);
			// 記事内で見つかった、hタグの数だけループします。
			foreach ( $matches as $m ) {
				$level = $m [1]; // 見つかったhタグのlevelを取得します。
				$text = $m [2]; // 見つかったhタグの、タグの中身を取得します。
				               // li, ulタグを閉じる処理です。2ループ目以降に中に入る可能性があります。
				               // 例えば、前回処理したのがh3タグで、今回出現したのがh2タグの場合、
				               // h3タグ用のulを閉じて、h2タグに備えます。
				while ( $current_level > $level ) {
					$current_level --;
					$outline .= '</li></ul>';
				}
				// 同じlevelの場合、liタグを閉じ、新しく開きます。
				if ($current_level == $level) {
					$outline .= '</li><li class="outline__item">';
				} else {
					// 同じlevelでない場合は、ul, liタグを追加していきます。
					// 例えば、前回処理したのがh2タグで、今回出現したのがh3タグの場合、
					// h3タグのためにulを追加します。
					while ( $current_level < $level ) {
						$current_level ++;
						$outline .= sprintf ( '<ul class="outline__list outline__list-%s"><li class="outline__item">', $current_level );
					}
					// 見出しのレベルが変わった場合は、現在のレベル以下の出現回数をリセットします。
					for($idx = $current_level + 0; $idx < count ( $sub_levels ); $idx ++) {
						$sub_levels [$idx] = 0;
					}
				}
				// 各レベルの出現数を格納する配列を更新します。
				$sub_levels [$current_level] ++;
				// 現在処理中のhタグの、パスを入れる配列を定義します。
				// 例えば、h2 -> h3 -> h3タグと進んでいる場合は、
				// level_fullpathはarray(1, 2)のようになります。
				// ※level_fullpath[0]の1は、1番目のh2タグの直下に入っていることを表します。
				// ※level_fullpath[1]の2は、2番目のh3を表します。
				$level_fullpath = array ();
				for($idx = $min_level; $idx <= $level; $idx ++) {
					$level_fullpath [] = $sub_levels [$idx];
				}
				$target_anchor = 'outline__' . implode ( '_', $level_fullpath );
				
				// 目次に、<a href="#outline_1_2">1.2 見出し</a>のような形式で見出しを追加します。
				$outline .= sprintf ( '<a class="outline__link" href="#%s"><span class="outline__number">%s.</span> %s</a>', $target_anchor, implode ( '.', $level_fullpath ), strip_tags ( $text ) );
				// 本文中の見出し本体を、<h3>見出し</h3>を<h3 id="outline_1_2">見出し</h3>
				// のような形式で置き換えます。
				$hid = preg_replace ( '/<h([1-6])/', '<h\1 id="' . $target_anchor . '"', $m [0] );
				$content = str_replace ( $m [0], $hid, $content );
			}
			// hタグのループが終了後、閉じられていないulタグを閉じていきます。
			while ( $current_level >= $min_level ) {
				$outline .= '</li></ul>';
				$current_level --;
			}
			// h1〜h6タグの個数
			$counter = count ( $matches );
		}
		return array (
				'content' => $content,
				'outline' => $outline,
				'count' => $counter 
		);
	}
	
	// 目次を作成します。
	private function add_outline($content) {
		
		// 目次を表示するために必要な見出しの数
		if (get_option ( 'fit_post_outline_number' )) {
			$number = get_option ( 'fit_post_outline_number' );
		} else {
			$number = 1;
		}
		// 目次関連の情報を取得します。
		$outline_info = $this->get_outline_info ( $content );
		$content = $outline_info ['content'];
		$outline = $outline_info ['outline'];
		$count = $outline_info ['count'];
		if (get_option ( 'fit_post_outline_close' )) {
			$close = "";
		} else {
			$close = "checked";
		}
		if ($outline != '' && $count >= $number) {
			// 目次を装飾します。
			$decorated_outline = sprintf ( '
		<div class="outline">
		  <span class="outline__title">目次</span>
		  <input class="outline__toggle" id="outline__toggle" type="checkbox" ' . $close . '>
		  <label class="outline__switch" for="outline__toggle"></label>
		  %s
		</div>', $outline );
			//
			// print_r(get_option('fit_post_outline'));
			// print_r(get_post_meta(get_the_ID(), 'outline_none', true) );
			
			if (get_option ( 'fit_post_outline' ) != 'value2' && get_post_meta ( get_the_ID (), 'outline_none', true ) != '1') { // && is_single()
				$shortcode_outline = '[outline]';
				if (strpos ( $content, $shortcode_outline ) !== false) {
					// 記事内にショートコードがある場合、ショートコードを目次で置換します。
					$content = str_replace ( $shortcode_outline, $decorated_outline, $content );
				} else if (preg_match ( '/<h[1-6].*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
    				// 最初のhタグの前に目次を追加します。
    				$pos = $matches[0][1];
    				$content = substr($content, 0, $pos) . $decorated_outline . substr($content, $pos);
    				
    			}
    			
    		}
    		
    	}
    	return $content;
    }

   
}
