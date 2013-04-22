<?


/*
* Software Engineer: Yuri Krupin, B.Sc. (Systems & Computing)
*
* index.php
* the website main page. Part of a custom CMS. Checks for permissions, updates and deletes
* Pulls specific, most recent articles, creates a banner and a menu out of SQL data
* ContentBox is the OOP core class. It is used to render articles and other content.
* Logged in admin sees the site with modification options, depending on content.

To Do
Cross Browswer Capabilities
Media Quieries
PDO = php databojects 

html5 css3

Localstorage



*/


include("/include/session.php");
require_once("/include/Blocks.php");
require_once("/include/ContentBox.php");

$database = new MySQLDB();
  //if admin desided to do stuff:
	if($session->isAdmin() || $session->userlevel >= EDITOR_LEVEL)
		{
		if($_GET['admin_deleteThis'])
		{
			$database->deleteTopic($_GET['admin_deleteThis']);
		}
	}
	
	if(isset($_POST['download']))
	{
		$database->addEmailGrab( $_SERVER['REMOTE_ADDR'], $_POST['user']  ,$_POST['email'] );
	}

	//$main_topics_array = $database->get_main_topics();
	
	// check for block updates
	if(isset($_POST['content'])){
			if(DEBUGGER){echo "<br/>";}
			if(DEBUGGER){echo "<br/> DEBUGGER: CONTENT _POSTed !!!!!!!!!! _POST[block_id] = ", $_POST['block_id'] ;}
			$database->insertUpdateUserContent($_POST['block_id'], $session->username , $_POST['content'] ,$_POST['content_type']);
	}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?renderHeaderContent();?>
	</head>
	<body onload="startBanner(1)">
	  <div align="center">
		<div class="main_div">
		  <div class="main">
			<div class="main_site">
			  <div class="logo_area">
				
				
				<?			
							if($session->isAdmin()){
								$box_id = 0;
								$thisContentBox = $database->getContentBox($box_id);
								echo (new ContentBox($box_id,$thisContentBox['content'],$thisContentBox['img'], $session->isAdmin(), "logo",null,$thisContentBox['page_link'] ));
							}
							
				?>
				
				<div class="logo">
				  <a href="/"><img src="images/logo.png" height="100%" alt="" hspace="27" border="0" /></a>
				</div>
				
				<div class= "user_menu">
				  				 <?
								// USER MENU
									   /**
									   * User has already logged in, so display relavent links, including
									   * a link to the admin center if the user is an administrator.
									   */
							   if($session->logged_in){
								  renderUserInfo($session->username, $session->isAdmin(),$database->get_new_msg_count($session->username));
							   }
							   else{
								  renderLogin(); //<-- $form is decleared here
								  renderLoginErrors($form);
								}
								?>
				</div>
	
			</div>
				
			<div class="super_centeredmenu">
					<ul class = "tabs">
						<?renderIsuranceListItems();?>	
					</ul>
			</div>
				
				
				
			  <!--div class="centeredmenu">
				SEARCH DO
			 </div -->

				<div class="header">
					
					<?
							$main_topics_array = $database->get_main_topics(4, null);
					?>	
					<div class="news">
							<?						
							if(!$main_topics_array)
							{
								if(DEBUGGER){echo "<br/> DEBUGGER(index) main_topics_array is empty";}
							}
							else
							{
								$n=0;
								foreach($main_topics_array as &$topic) 
								{ // Start looping table row
									$n++;
									$imglink = ($topic['img'])?$topic['img']:"/upload/066_29566.jpg";
									echo (new ContentBox($topic['id'],$topic['topic'],$imglink, ($session->isAdmin() || $session->userlevel >= EDITOR_LEVEL), "news_item", $n ));
								}
							}
							?>
					</div>
					
					
					
					<div class ="banner_container">
							<?						
							if(!$main_topics_array)
							{
								if(DEBUGGER){echo "<br/> DEBUGGER(index) main_topics_array is empty";}
							}
							else
							{
								$n=0;
								foreach($main_topics_array as &$topic) 
								{ // Start looping table row
									$n++;
									$imglink = ($topic['img'])?$topic['img']:"/upload/066_29566.jpg";
									echo (new ContentBox($topic['id'],$topic['topic'],$imglink, $session->isAdmin(), "banner", $n ));
								}
							}
							?>
					</div>  
				</div>

			<div class="body_area">
	
				<div class="content_area">
					<div class="planboxes_area">
						<div class = 'inner_header'> <h1>Auto Insurance</h1></div><br/><br/>
							
							<?
							$main_topics_array = $database->get_main_topics(3, "Auto");
							if(!$main_topics_array)
							{
								if(DEBUGGER){echo "<br/> DEBUGGER(index) main_topics_array is empty";}
							}
							else
							{
								foreach($main_topics_array as &$row) 
								{  
									/*Display articles as teasers*/
									$detail = $database->get_topic_data_limited($row['id'],300);
									$content_str = "<h2>".$row['topic']."</h2>".$detail['detail'];
									$imglink = ($row['img'])?$row['img']:"/upload/066_29566.jpg";
 									echo (new ContentBox($row['id'],$content_str,$imglink, $session->isAdmin(), "article_box" ));
								}
							}
							
							?>
					</div>

					<div class="planboxes_area">
							<div class = 'inner_header'> <h1>Commercial Insurance</h1></div><br/><br/>
							<?
							
							$main_topics_array = $database->get_main_topics(3, "News");
							if(!$main_topics_array)
							{
								if(DEBUGGER){echo "<br/> DEBUGGER(index) main_topics_array is empty";}
							}
							else
							{
								foreach($main_topics_array as &$row) 
								{ 
									/*Display articles as teasers*/
									$detail = $database->get_topic_data_limited($row['id'],300);
									$content_str = "<h2>".$row['topic']."</h2>".$detail['detail'];
									$imglink = ($row['img'])?$row['img']:"/upload/066_29566.jpg";
 									echo (new ContentBox($row['id'],$content_str,$imglink, $session->isAdmin(), "article_box" ));
								}
							}
							
							
						?>
					</div>
						
				</div>
					
					
					
			</div>

				
				<div class="lower_content">
					<div class='lower_content_box'><?renderInfo();?></div>
					<div class='lower_content_box'><?renderWhyUs();?></div>
					<div class='lower_content_box'><?renderInfo();?></div>
					<div class='lower_content_box'><?renderContacts();?></div>
				</div>
				<div class="footer">
					&copy; <?renderTitle();?> <?echo date("Y");?>
				</div>
				
			</div>
		  </div>
		</div>
	  </div>
	</body>
</html>






