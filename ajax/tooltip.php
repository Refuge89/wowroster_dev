<?php

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$type = $id = '';
if ($roster->pages[1] != '')
{
	$type = $roster->pages[1];
}
else
{
	return 'Error';
	exit();
}
if ($_GET['id'] != '')
{
	$id = $_GET['id'];
}
else
{
	return 'Error';
	exit();
}

	$output = '';
	//echo '<pre>';print_r($api->act);
	switch ($type)
	{
		case 'itemptr':
			$var = file_get_contents('http://ptr.wowhead.com/item='.$id.'?power');
			$ret = str_replace("\$WowheadPower.registerItem('".$id."', 'ptr', ", '', $var);
			$ret = str_replace(");", '', $ret);
			$ret = str_replace('"', '\"', $ret);
			$ret = preg_replace("/\t(\w*)\:/", '"\1":', $ret);
			$ret = preg_replace("/\ '(.*)',/", '"\1",', $ret);
			$ret = preg_replace("/\ '(.*)'/", '"\1"', $ret);
			$ret = preg_replace("/q(\d{1})/", 'item-\1', $ret);
			//echo $ret;
			//class="q4"
			
			$ret = str_replace("\'", "'", $ret);
			$d = json_decode($ret,true);
			
			$output = stripslashes($d['tooltip_ptr']);
			
			
		break;
		
		case 'item':

			list($id1,$member_id) = explode('|',$id);
			list($item_id,$enchant,$gem0,$gem1,$gem2,$gem3,$suffixID,$uniqueID,$level,$reforgeId,$upgradeId,$bonus1,$bonus2,$bonus3,$bonus4,) = explode(':',$id1);
			
			$query12  = " SELECT *"
			. " FROM `" . $roster->db->table('items') . "`"
			. " WHERE `member_id` = '$member_id'"
			. " AND `item_id` = '$id1';";

			$result12 = $roster->db->query($query12);
			$da = $roster->db->fetch($result12);
			$uitem = json_decode($da['json'],true);
			
			$params = array();
			$params['id'] = $item_id;
			$base_item = $roster->api2->fetch('item',$params);
			if (isset($uitem['bonusLists']) && !empty($uitem['bonusLists']))
			{
				$params['bl'] = implode(',',$uitem['bonusLists']);
			}
			//if (isset($uitem['context']) && !empty($uitem['context']) && $uitem['context'] != 'quest-reward' && $uitem['context'] != 'vendor')
			//if (isset($uitem['availableContexts']) && !empty($uitem['availableContexts']) && $items['context'] != 'quest-reward')
			if (isset($uitem['context']) && !empty($uitem['context']) && in_array($uitem['context'], $base_item['availableContexts']) )
			{
				$params['context'] = $uitem['context'];
			}
			/*
			{
				$params['id'] .= "/".$uitem['context'];
			}
			*/
			if (isset($enchant))
			{
				$enchant = $enchant ? $enchant : null;
			}	
			$gemx = array();
			$item = $roster->api2->fetch('item',$params);//$roster->api->Data->getItemInfo($item_id);
			if (isset( $item['id']))
			{
				if (isset($gem0) && !empty($gem0))
				{
					$g0 = $roster->api2->fetch('item',array('id'=>$gem0));//$roster->api->Data->getItemInfo($gem0);
					$gemx['gem0'] = $g0;
					$gemx['gem0']['Tooltip'] = api_escape($g0['name']."<br>".$g0['gemInfo']['bonus']['name']."<br>".$g0['description']);
					$gemx['gem0']['gem_bonus'] = api_escape($g0['gemInfo']['bonus']['name']);
					$gemx['gem0']['gem_color'] = strtolower($g0['gemInfo']['type']['type']);
				}
				if (isset($gem1) && !empty($gem1))
				{
					$g1 = $roster->api2->fetch('item',array('id'=>$gem1));//$roster->api->Data->getItemInfo($gem1);
					$gemx['gem1'] = $g1;
					$gemx['gem1']['Tooltip'] = api_escape($g1['name']."<br>".$g1['gemInfo']['bonus']['name']."<br>".$g1['description']);
					$gemx['gem1']['gem_bonus'] = api_escape($g1['gemInfo']['bonus']['name']);
					$gemx['gem1']['gem_color'] = strtolower($g1['gemInfo']['type']['type']);
				}
				if (isset($gem2) && !empty($gem2))
				{
					$g2 = $roster->api2->fetch('item',array('id'=>$gem2));//$roster->api->Data->getItemInfo($gem2);
					$gemx['gem2'] = $g2;
					$gemx['gem2']['Tooltip'] = api_escape($g2['name']."<br>".$g2['gemInfo']['bonus']['name']."<br>".$g2['description']);
					$gemx['gem2']['gem_bonus'] = api_escape($g2['gemInfo']['bonus']['name']);
					$gemx['gem2']['gem_color'] = strtolower($g2['gemInfo']['type']['type']);
				}
				if (isset($gem3) && !empty($gem3))
				{
					$g3 = $roster->api2->fetch('item',array('id'=>$gem3));//$roster->api->Data->getItemInfo($gem3);
					$gemx['gem3'] = $g3;
					$gemx['gem3']['Tooltip'] = api_escape($g2['name']."<br>".$g3['gemInfo']['bonus']['name']."<br>".$g3['description']);
					$gemx['gem3']['gem_bonus'] = api_escape($g3['gemInfo']['bonus']['name']);
					$gemx['gem3']['gem_color'] = strtolower($g3['gemInfo']['type']['type']);
				}
				
				
				$item_color = $roster->api->Data->_setQualityc($item['quality']);
				$html_tooltip = $roster->api->Item->item($item,$uitem,$gemx);
				$i = array();
				$i['item_id'] 			= $item['id'].':'.$enchant.':'.$gem0.':'.$gem1.':'.$gem2.':'.$gem3;
				$i['item_name'] 		= $item['name'];
				$i['item_level'] 		= $item['itemLevel'];
				$i['level'] 			= $item['requiredLevel'];
				$i['item_texture'] 		= $item['icon'];
				$i['item_tooltip']		= $html_tooltip;
				$i['item_color'] 		= $item_color;
				$i['item_quantity'] 	= $item['quality'];
				$i['item_slot'] 				= '';
				$i['item_parent'] 			= '';
				$i['member_id'] 		= '';
				$i["Gem"]= array();
				foreach($gemx as $gid => $gd)
				{
					$gm = array();
					$gm["gemID"] = $gd['id'];
					$gm["Name"] = $gd['name'];
					$gm["Link"] = "|cff".$roster->api->Item->_getItemColor($gd['quality'])."|Hitem:".$gd['id'].":0:0:0:0:0:0:0:".$gd['itemLevel'].":0|h[".$gd['name']."]|h|r";
					$gm["Color"] = $roster->api->Item->_getItemColor($gd['quality']);
					$gm["Tooltip"] = $gd['Tooltip'];
					$gm["Icon"] = $gd['icon'];
					$gm["Item"] = $gd['id'];
					$i["Gem"][] = $gm;
				}
				//echo '<pre>';
				//print_r($i);
				//echo '</pre>';
				require(ROSTER_LIB.'item.php');
				$x = new item($i,'full');
				
				$it = $x->html_tooltip;
				$it = str_replace('<br><br><br>',"<br>",$it);
				$it .= '<br>(debug "'.$item_id.'")';
				$output = $it;
			}
		break;
		
		case 'spell':

			$a = $roster->api2->fetch('spell',array('id'=>$id));

			$roster->tpl->assign_vars(array(
					'TYPE'			=> 'spell',
					'NAME'			=> $a['name'],
					'DESCRIPTION'	=> str_replace('\n\n','<br>',$a['description']),//$a['description'],
					'RANGE'			=> $a['range'],
					'POWERCOST'		=> $a['powerCost'],
					'CASTTIME'		=> $a['castTime'],
					'COOLDOWN'		=> $a['cooldown'],
				)
			);
			$roster->tpl->set_filenames(array('tooltip' => 'api.html'));
			$output = $roster->tpl->fetch('tooltip');
		break;
		
		case 'talent':
		
			$a = $roster->api2->fetch('spell',array('id'=>$id));

			$roster->tpl->assign_vars(array(
					'TYPE'			=> 'talent',
					'NAME'			=> $a['name'],
					'DESCRIPTION'	=> str_replace('\n\n','<br>',$a['description']),//$a['description'],
					'RANGE'			=> $a['range'],
					'POWERCOST'		=> $a['powerCost'],
					'CASTTIME'		=> $a['castTime'],
					'COOLDOWN'		=> $a['cooldown'],
				)
			);
			$roster->tpl->set_filenames(array('tooltip' => 'api.html'));
			$output = $roster->tpl->fetch('tooltip');
		break;
		
		case 'glyph':
		
			$sqlg = "SELECT * FROM `api_data_glyphs` WHERE `id` = '".$id."' ";
			$resultg = $roster->db->query($sqlg);
			$rowg = $roster->db->fetch($resultg);
			$tooltip = '';
			$tooltip .= $rowg['name'].'<br>';
			$tooltip .=	(isset($rowg['subtext']) ? ''.$rowg['subtext'].'<br>' : '');
			$tooltip .=	(isset($rowg['htmlDescription']) ? $rowg['htmlDescription'] : '');
			$output	= $tooltip;//$tooltip;
		break;
		
		case 'text':
			$body = $_POST['tooltip'];		
			$body = substr($body, 5);//str_replace("text-", "", $_POST['tooltip']);
			$v = '';
			if (isset($_POST['caption']))
			{
				$v = base64_decode( $_POST['caption'] );
			}
			$output = $v.base64_decode( $body );
		break;
		
		case 'char':
		
			list($sr, $name) = explode('|', $id);
			list($server, $region) = explode(':', $sr);
			
			$server = str_replace('+','-',$server);
			//$api = new WowAPI(swregion($region));
			$char = $roster->api2->fetch('character',array('name'=>$name,'server'=>$server,'fields'=>'guild,talents'));//$roster->api->Char->getCharInfo($server,$name,'1:3');
			
		
			$class=$race='';
		
			$class = $roster->api->act['id_to_class'][$char['class']];
			$race = $roster->api->act['id_to_race'][$char['race']];
			$t_icon = $t_name = '';
			foreach ($char['talents'] as $key => $spec)
				{
					if (isset($spec['selected']) && $spec['selected'] ==1 )
					{
						$t_icon = $spec['spec']['icon'];
						$t_name = $spec['spec']['name'];
					}
				}
			$output = '<div class="character-tooltip">
						<div class="cicon">
							<span class="icon-frame frame-56">
								<img src="http://'.strtolower($region).'.battle.net/static-render/'.strtolower($region).'/'.$char['thumbnail'].'" alt="" width="56" height="56" />
								<span class="frame"></span>
							</span>
						</div>
						<div class="cinfo">
							<div class="cname">'.$char['name'].'</div>
							<div class="class' . str_replace(' ','',$class) . 'txt">
									'.$char['level'].' '.$race.' '.$class.'
							</div>
							<div class="color-tooltip-alliance">
								'.$char['guild']['name'].'
							</div>
							<div class="color-tooltip-yellow">'.$char['realm'].'</div>
							<span class="clear"><!-- --></span>
							<span class="character-spec">
								<span class="icon">
									<span class="icon-frame frame-18 ">
										<img src="http://media.blizzard.com/wow/icons/18/'.$t_icon.'.jpg" alt="" class="img-middle" width="18" height="18" />
										<span class="frame"></span>
									</span>
								</span>
								<span class="name"> '.$t_name.'</span>
								<span class="clear"><!-- --></span>
							</span>
						</div>
					</div>';
		
		
		break;
	}
	
	echo $output;
	//       include_once (ROSTER_BASE . 'footer.php');
	exit();
	
	
	
	function api_escape( $string )
	{
		if( version_compare( phpversion(), '4.3.0', '>' ) )
		{
			return mysql_real_escape_string( $string );
		}
		else
		{
			return mysql_escape_string( $string );
		}
	}
	