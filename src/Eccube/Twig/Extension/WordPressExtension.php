<?php

namespace Eccube\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
//require_once (WORDPRESS_PATH . "/wp-blog-header.php");

class WordPressExtension extends AbstractExtension {
	public function getFunctions() {
		return [
				new TwigFunction ( 'getPosts', [ $this,'getPosts' ] ) ,
				new TwigFunction ( 'getGuids', [ $this,'getGuids' ] ),
			    new TwigFunction ( 'getNotices', [ $this,'getNotices' ] )


		];
	}
	public function getPosts($number=4) {


		$args = array (
				'numberposts' => $number,
				'order' => 'DESC',
				'post_status' => 'publish' ,
		);
		//mysql://ewin:ec_Cube123@mysql647.db.sakura.ne.jp:3306/ewin_ec-shop
         //$con = mysqli_connect("127.0.0.1","root","root","wp1");
		$con = mysqli_connect("localhost","root","office.com","ewin_wp_blog");
		mysqli_set_charset($con, "utf8");
		$result = mysqli_query($con,"  SELECT wp4e9bb4posts.* FROM wp4e9bb4posts ,wp4e9bb4term_relationships where wp4e9bb4posts.ID=wp4e9bb4term_relationships.object_id and wp4e9bb4term_relationships.term_taxonomy_id in (2,4,5,9,11,8,6,3)  and  wp4e9bb4posts.post_status='publish' and wp4e9bb4posts.ping_status='open' order by wp4e9bb4posts.post_date desc limit 0, " .$number);
		//$result = mysqli_query($con,"SELECT * FROM wp_posts   where post_status='publish' order by post_date desc limit 0, " .$number);

         /*
		$recentPostArgs = array(
		'numberposts' => $number,
        'post_status' => 'publish'

		);

		$recent_posts=wp_get_recent_posts($recentPostArgs);
		log_info('查询结果', [$recent_posts]);*/

		$deviceToken=array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
          $deviceToken[] = $row;
        }
		//log_info('查询结果2222', $deviceToken);
		//
        $posts=array();

		foreach ( $deviceToken as $el ) {
			//$url=$this->get_image_url($el['post_content']);

			$imgResult = mysqli_query($con, "select guid from  wp4e9bb4posts where id in  (select  a.meta_value from wp4e9bb4postmeta a, wp4e9bb4posts b where a.post_id=b.id and a.post_id=".$el['ID']."  and a.meta_key='_thumbnail_id')");
			log_info('sql：', ['sql'=>"select guid from  wp4e9bb4posts where id in  (select  a.meta_value from wp4e9bb4postmeta a, wp4e9bb4posts b where a.post_id=b.id and a.post_id=".$el['ID']."  and a.meta_key='_thumbnail_id')"]);
			 while ($rowi = $imgResult->fetch_array(MYSQLI_ASSOC)) {
				 log_info('查询结果2333', ['rowi'=>$rowi]);
				 if($rowi!=null){
				 $url=$rowi['guid'];
				  break;
				 }
                // $imgURL[] = $rowi;
            }


			//$url=get_the_post_thumbnail_url($el['ID'],$size = 'post-thumbnail');
			log_info('查询结果2333', ['imgURL'=>$url]);


			if($url==null){
				$url="/html/template/default/assets/img/default-thumb.jpg";
			}
			$abstract=$this->getAbstract($el['post_content']);
			$postAndImage=[
					'title'=>$el['post_title'],
					'image'=>$url,
					'abstract'=>$abstract,
				    'ID'=>$el['ID']
			];
			array_push($posts, $postAndImage);
			log_info('查询结果2333', $postAndImage);

		}
		mysqli_close($con);
        log_info('查询结果111', $posts);
		//
		return $posts;
	}

	private function getAbstract($content){
		$abstract=strip_tags($content);
		//$abstract=substr($abstract,0,410);
		return $abstract;
	}
	private function get_image_url($content){
		preg_match("%src=\"([^\"])+(png|jpg|gif)\"%i",$content,$result);
		return $result;
	}

	public function getGuids($number=5) {


		$args = array (
				'numberposts' => $number,
				'order' => 'DESC',
				'post_status' => 'publish' ,
		);
		//mysql://ewin:ec_Cube123@mysql647.db.sakura.ne.jp:3306/ewin_ec-shop
		// $con = mysqli_connect("127.0.0.1","i9441627_wp1","K.r1fy6wm0tzDUvKj8182","i9441627_wp1");
		$con = mysqli_connect("localhost","root","office.com","ewin_wp_blog");
		mysqli_set_charset($con, "utf8");
		$result = mysqli_query($con,"  SELECT wp4e9bb4posts.* FROM wp4e9bb4posts ,wp4e9bb4term_relationships where wp4e9bb4posts.ID=wp4e9bb4term_relationships.object_id and wp4e9bb4term_relationships.term_taxonomy_id in (7)  and  wp4e9bb4posts.post_status='publish' and wp4e9bb4posts.ping_status='open' order by wp4e9bb4posts.post_date desc limit 0, " .$number);
		//$result = mysqli_query($con,"SELECT * FROM wp_posts   where post_status='publish' order by post_date desc limit 0, " .$number);
		mysqli_close($con);
		log_info('查询结果', [$result]);
		return $result;
	}

		public function getNotices($number=5) {


		$args = array (
				'numberposts' => $number,
				'order' => 'DESC',
				'post_status' => 'publish' ,
		);
		//mysql://ewin:ec_Cube123@mysql647.db.sakura.ne.jp:3306/ewin_ec-shop
		//  $con = mysqli_connect("127.0.0.1","i9441627_wp1","K.r1fy6wm0tzDUvKj8182","i9441627_wp1");
		$con = mysqli_connect("localhost","root","office.com","ewin_wp_blog");
		mysqli_set_charset($con, "utf8");
		$result = mysqli_query($con,"  SELECT wp4e9bb4posts.* FROM wp4e9bb4posts ,wp4e9bb4term_relationships where wp4e9bb4posts.ID=wp4e9bb4term_relationships.object_id and wp4e9bb4term_relationships.term_taxonomy_id in (12,7)  and  wp4e9bb4posts.post_status='publish' and wp4e9bb4posts.ping_status='open' order by wp4e9bb4posts.post_date desc limit 0, " .$number);
		//$result = mysqli_query($con,"SELECT * FROM wp_posts   where post_status='publish' order by post_date desc limit 0, " .$number);
		mysqli_close($con);
		log_info('查询结果', [$result]);
		return $result;
	}
}
