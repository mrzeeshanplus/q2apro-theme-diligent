<?php

/*
	Theme Name: q2apro Diligent
	Theme Description: A handy theme for your q2a forum that is a delight for the eyes
	Theme URI: http://www.q2apro.com/themes/diligent
	Theme Version: 1.0
	Theme Date: 2014-02-24
	Theme Author: q2apro.com
	Theme Author URI: http://www.q2apro.com
	Theme Minimum Question2Answer Version: 1.5
	
	Licence: Copyright © q2apro.com - All rights reserved	
*/


	class qa_html_theme extends qa_html_theme_base {
		
		// language strings and theme settings
		var $twitterUsername = 'fill-in-your-twitter-name';
		var $googlePlusLink = 'https://plus.google.com/';
		var $facebookLink = 'https://www.facebook.com/';
		var $facebookBoxEnabled = false; // or true
		
		var $askNowLabel = 'ask now - it\'s free';
		var $askQuestionString = 'What do you like to know?';
		var $askQbuttonLabel = 'Ask now';
		var $bestLabel = 'Best';
			
		var $sp_intromain = 'Get free help from experts';
		var $sp_introexp1 = 'Ask your question';
		var $sp_introexp2 = 'Ask for free and without registration';
		var $sp_introdisc1 = 'Discuss with users';
		var $sp_introdisc2 = 'Share your problem with experts and get help';
		var $sp_introprob1 = 'Problem solved';
		var $sp_introprob2 = 'Vote on answers and select the best one';


		
		// override subnavigation to place nav('sub') into sidepanel layer
		function nav_main_sub() {
			$this->nav('main');
		}
		
		// override sidepanel
		function sidepanel()
		{
			// output css element (for background)
			$this->output('<div class="content-flow"><div class="content-wrapper">');
			
			// moved sub-nav to inner
			$this->nav('sub');
			
			$this->output('<div class="qa-sidepanel">');
			
			$this->widgets('side', 'top');
			$this->sidebar();
			
			// big business button, only on certain pages
			if($this->template=='qa' || $this->template=='questions' || $this->template=='question' || $this->template=='unanswered') {
				$this->output('<a class="btnyellow oranged askbtn_sidebar" href="'.qa_path_html('ask').'">'.$this->askNowLabel.'</a>');
			}
				
			if($this->template=='qa' || $this->template=='question' || $this->template=='questions' || $this->template=='unanswered') {
				if($this->facebookBoxEnabled) {
					$sharelink = urlencode(qa_opt('site_url'));
					$this->output('<iframe class="fbookframe" src="https://www.facebook.com/plugins/like.php?href='.$sharelink.'&amp;width=120&amp;height=35&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;send=false&locale=en_US" scrolling="no" frameborder="0" allowTransparency="true"></iframe>');
				}
			}
			
			$this->widgets('side', 'high');
			$this->nav('cat', 1);
			$this->widgets('side', 'low');
			$this->output_raw(@$this->content['sidepanel']);
			$this->feed();
			$this->widgets('side', 'bottom');
			$this->output('</div> <!-- qa-sidepanel -->');
		}

		// override main to output own html elements and more
		function main() {
			// output identifier for anonymous, needed for some JS plugins
			if(!qa_is_logged_in()) {
				$this->output('<div id="isAnonym"></div>');
			}
			// teaser to ask using an askbar, only show for visitors that are not logged in 
			if(!qa_is_logged_in() && ($this->template=='qa' || $this->template=='question' || $this->template=='activity' || $this->template=='questions')) {
				?>
				<div class="qa-ask-box">
					<form method="post" action="<?php echo qa_path_html('ask', null); ?>">
						<input id="askboxin" name="title" type="text" class="qa-form-tall-text" placeholder="<?php echo $this->askQuestionString; ?>">
						<input class="ask-box-button" type="submit" value="<?php echo $this->askQbuttonLabel; ?>">
						<input type="hidden" name="doask1" VALUE="1">
					</form>
				</div>
				<?php
			}
			// default call
			qa_html_theme_base::main();
		}

		// output embed css font
		function head_css() {
			$this->output('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400&amp;subset=latin,latin-ext" type="text/css" />');
			$this->output('<style type="text/css">
				.qa-q-list .qa-a-count-selected:after, .qa-useract-wrapper .qa-a-count-selected:after,
				.qa-a-selection:after {
					content:"'.$this->bestLabel.'";
				}
				.qa-a-item-selected>.qa-a-selection:after {
					content:"";
				}
			</style>');

			// default
			qa_html_theme_base::head_css();
		}

		// viewport for mobiles
		function head_metas() {
			qa_html_theme_base::head_metas();
			$this->output('<meta name="viewport" content="width=device-width, initial-scale=1.0" >');
		}

		// override head_script() to insert jquery CDN script
		function head_script() {
			if (isset($this->content['script'])) {
				foreach ($this->content['script'] as $scriptline) {
					if( strpos($scriptline, 'jquery') === false ) {
						$this->output_raw($scriptline);
					}
					else {
						$scriptline = str_replace('</', '<\/', $scriptline);
						$this->output_raw('<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>   <script type="text/javascript">window.jQuery || document.write(\''.$scriptline.'\')</script>'); 
					}
				}
			}
		}
		
		// override body_content
		function body_content()
		{
			$this->body_prefix();
			$this->notices();
			
			$this->output('<div class="qa-body-wrapper">', '');

			// flag for mobiles
			if(qa_is_mobile_probably()) {
				$this->output('<div id="agentIsMobile"></div>');  // important: mobiles-identifier for jquery
			}
			
			$this->widgets('full', 'top');
			$this->header();

			// BIG intro on startpage
			if($this->template=='qa') {
				$this->output('
				<div class="sp_content">
					<div class="container">
						<div class="row">
							<div class="sp_header"> 
								<p>'.$this->sp_intromain.'</p>
							</div> 
							<div class="sp_item"> 
								<div class="desc"> 
									<a href="/ask">'.$this->sp_introexp1.'</a> 
									<p>'.$this->sp_introexp2.'</p> 
								</div> 
								<div class="icon icon-question"></div>
							</div>
							<div class="sp_item">
								<div class="desc"> 
									<a href="/activity">'.$this->sp_introdisc1.'</a> 
									<p>'.$this->sp_introdisc2.'</p>
								</div>
								<div class="icon icon-comment"></div>
							</div> 
							<div class="sp_item">
								<div class="desc"> 
									<a href="/questions">'.$this->sp_introprob1.'</a>
									<p>'.$this->sp_introprob2.'</p>
								</div>
								<div class="icon icon-checkmark"></div>
							</div>
						</div>
					</div>
				</div>
				');
			}
			$this->widgets('full', 'high');
			$this->sidepanel();
			$this->main();
			$this->widgets('full', 'low');
			$this->footer();
			$this->widgets('full', 'bottom');
			
			// close additional css-div
			$this->output('
						</div> <!-- content-wrapper -->
				</div> <!-- content-flow -->
			');  
			$this->output('</div> <!-- body-wrapper -->');

			$this->body_suffix();
		}

		// add logo next to title
		/*
		function logo() {
			$this->output(
				'<div class="qa-logo"><span style="font-size:13px;color:#EFEFEF;">Answers and questions</span><br />', 
				'<a href="#" class="qa-logo-link">Forum Name <span class="ktlogo"></span></a>',
				'</div>'
			);
		}
		*/

		// override for pagination questions on activity page
		function page_links() {
			$page_links=@$this->content['page_links'];
			
			if (!empty($page_links)) {
				$this->output('<div class="qa-page-links">');
				
				$this->page_links_label(@$page_links['label']);
				$this->page_links_list(@$page_links['items']);
				$this->page_links_clear();
				
				$this->output('</div>');
			}
			// added pagination on qa page
			else if($this->template=='qa' && $this->request=='') {
				$pagesize = qa_opt('page_size_qs');
				$i = 1;
				$this->output('<div class="qa-page-links">');
				$this->output('
					<ul class="qa-page-links-list" style="margin-top:10px;">
						<li CLASS="qa-page-links-item">
							<a href="./questions?start=0" CLASS="qa-page-link">1</a>
						</li>
						<li CLASS="qa-page-links-item">
							<a href="./questions?start='.($i++*$pagesize).'" CLASS="qa-page-link">2</a>
						</li>
						<li CLASS="qa-page-links-item">
							<a href="./questions?start='.($i++*$pagesize).'" CLASS="qa-page-link">3</a>
						</li>
						<li CLASS="qa-page-links-item">
							<a href="./questions?start='.($i++*$pagesize).'" CLASS="qa-page-link">4</a>
						</li>
						<li CLASS="qa-page-links-item">
							<span class="qa-page-ellipsis">…</span>
						</li>
					</ul>
				');
				$this->page_links_clear();
				$this->output('</div>');
			}
		}

		/* override to add custom CSS class in body tag */
		function body_tags() {
			$class='qa-template-'.qa_html($this->template);
			if($this->request=='rewards'){
				$class='qa-template-page-rewards';
			}
			if (isset($this->content['categoryids']))
				foreach ($this->content['categoryids'] as $categoryid)
					$class.=' qa-category-'.qa_html($categoryid);
			
			$this->output('class="'.$class.' qa-body-js-off"');
		}

		/* SHAREBOX */
		function q_view_buttons($q_view) {
			$shareUrl = qa_q_path($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title'], true);
			$shareUrlKT = qa_opt('site_url').$this->content['q_view']['raw']['postid'];
			$shareUrlEnc = urlencode($shareUrl);
			$this->output('
			<div class="sharebox">
				<a class="shlink tooltipS" href="'.$shareUrlKT.'"></a>
				<a class="shprint tooltipS" href="javascript:window.print();"></a>
				<a class="shfb tooltipS" href="https://www.facebook.com/sharer.php?u='.$shareUrlEnc.'"></a>
				<a class="shgp tooltipS" href="https://plus.google.com/share?url='.$shareUrlEnc.'"></a>
				<a class="shtw tooltipS" href="https://www.twitter.com/share?url='.$shareUrlEnc.'"></a>
			</div>');

			// default method call
			qa_html_theme_base::q_view_buttons($q_view);
		}

		function footer() {
			$this->output('<div class="qa-footer">');
			$this->nav('footer');
			$this->output('<a class="qa-theme-notice" href="https://www.q2apro.com/">Theme by <u>q2apro</u></a>');
			$this->footer_clear();			
			// social buttons in footer
			$this->output('<a class="foot_tw" target="_blank" href="https://twitter.com/'.$this->twitterUsername.'"></a>
				<a class="foot_gp" target="_blank" href="'.$this->googlePlusLink.'"></a>
				<a class="foot_fb" target="_blank" href="'.$this->facebookLink.'"></a>
			');
			$this->output('</div> <!-- END qa-footer -->', '');
		}
		
		function a_count($post) {
			// You can also use $post['answers_raw'] to get a raw integer count of answers
			$this->output_split(@$post['answers'], 'qa-a-count', 'span', 'span',
				@$post['answer_selected'] ? 'qa-a-count-selected' : (@$post['answers_raw'] ? null : 'qa-a-count-zero'));
				
			// is votes show them on question list
			if(isset($post['vote_view']) && $this->template!='question') {
				$quvotes = $post['raw']['upvotes'];
				if($quvotes>0) {
					$this->output('<span class="quvotes">'.$post['raw']['upvotes'].'</span>');
				}
			}
		}

		// override to not display :after element for best answer button (relies on qa-a-selection)
		function a_selection($post)
		{
			if( isset($post['select_tags']) || isset($post['unselect_tags']) || $post['selected'] || isset($post['select_text']) ) {
				$this->output('<div class="qa-a-selection">');
				
				if (isset($post['select_tags']))
					$this->post_hover_button($post, 'select_tags', '', 'qa-a-select');
				elseif (isset($post['unselect_tags']))
					$this->post_hover_button($post, 'unselect_tags', '', 'qa-a-unselect');
				elseif ($post['selected'])
					$this->output('<div class="qa-a-selected">&nbsp;</div>');
				
				if (isset($post['select_text']))
					$this->output('<div class="qa-a-selected-text">'.@$post['select_text'].'</div>');
				
				$this->output('</div>');
			}
		}

		/* Question Content below Question Title */
		/*
		function q_item_title($q_item) {
		
			$blockwordspreg = qa_get_block_words_preg();
			$maxlength = qa_opt('mouseover_content_max_len');
			
			$result=qa_db_query_sub('SELECT postid, content, format FROM ^posts WHERE postid IN (#)', $q_item['raw']['postid']);
			$postinfo=qa_db_read_all_assoc($result, 'postid');
			$thispost=@$postinfo[$q_item['raw']['postid']];
			
			$text = qa_viewer_text($thispost['content'], $thispost['format'], array('blockwordspreg' => $blockwordspreg));
			$text = strip_tags($text); // removes all img, p, etc. tags
			$text = qa_shorten_string_line($text, $maxlength);
			$q_preview = '<p>'.qa_html($text).'</p>'; // for full question content use: $thispost['content']

			$this->output(
				'<div class="qa-q-item-title">
				<a href="'.$q_item['url'].'">'.$q_item['title'].'</a>'
				.$q_preview.
				'</div>'
			);
		}
		*/
		
	} // END
	

/*
	Omit PHP closing tag to help avoid accidental output
*/