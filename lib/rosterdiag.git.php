<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster diagnostics and info
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @package    WoWRoster
 * @subpackage RosterDiag
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

//error_reporting(E_ALL);

//!!!!!!!!!!!!!// Developer Config //!!!!!!!!!!!!!//
// As a NON-Developer, please do not modify any content of this file, or the version check might fail!!!

// Ignored Directories
$ignored_dirs = array('.', '..', 'SVN', '.svn', '/img/Interface', './img/Interface/', 'TalentFrame', './img/Interface/Icons', './img/Interface/TalentFrame', 'cache','.git');

// Files to check with extension:
$extensions = array('php', 'inc', 'css', 'js', 'tpl', 'htm', 'html', 'jpg', 'gif', 'png', 'sql', 'txt');

// Files to ignore
$ignored_files = array('conf.php', '.htaccess', 'roster_addons_go_here.txt');

// Do we want to check the SubDirs ?? I think we do :)
$subdirectories = 1;

$local_file = ROSTER_CACHEDIR.'local.json';
$remote_file = ROSTER_CACHEDIR.'remote.json';

if( !file_exists($remote_file) )
{
	// get repo sha to pull
	$r = $roster->api->Git->GetRepo();
	$r_sha = $r[0]['object']['sha'];
	$re = $roster->api->Git->GetFiles($r_sha);
	//build a better recersive tree
	$remote = array();
	foreach ($re['tree'] as $e => $d)
	{
		$p = $n = '';
		if ($d['type'] == 'tree')
		{
			$p = $d['path'];
		}
		if ($d['type'] == 'blob')
		{
			$p = './'.$d['path'];
			$path_parts = pathinfo($p);
			
			if(CheckExtension($path_parts['basename']))
			{
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionFile'] = $path_parts['basename'];
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionMD5'] = $d['sha'];
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionSize'] = $d['size'];
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionDesc'] = '';
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionDate'] = '';
				$remote[$path_parts['dirname']][$path_parts['basename']]['remote']['versionAuthor'] = '';
			}
		}
	}
	file_writer( $remote_file , json_encode($remote) , $mode='wb' );
}
else
{
	$remote = json_decode(file_get_contents($remote_file), true);
}
// Set the severity information
$problemsev['description'] = 9;
$problemsev['revisiongreater'] = 4;
$problemsev['revisionsmaller'] = 9;
$problemsev['dateolder'] = 9;
$problemsev['dateyounger'] = 4;
$problemsev['author'] = 8;
$problemsev['MD5'] = 7;
$problemsev['MD5binary'] = 7;
$problemsev['nosvn'] = 2;
$problemsev['nolocal'] = 3;
$problemsev['unknown'] = 1;

$severity[0] = array('style' => 'sgreen',  'color' => '#12C312', 'weight' => 0,  'severityname' => 'No Issues');
$severity[1] = array('style' => 'sgray',   'color' => '#AFAFAF', 'weight' => 0,  'severityname' => 'Unknown');
$severity[2] = array('style' => 'sgray',   'color' => '#AFAFAF', 'weight' => 0,  'severityname' => 'Not in Repo');
$severity[3] = array('style' => 'sgray',   'color' => '#AFAFAF', 'weight' => 0,  'severityname' => 'Not in Local');
$severity[4] = array('style' => 'sblue',   'color' => '#312CF8', 'weight' => 0,  'severityname' => 'Newer Files');
$severity[5] = array('style' => 'sblue',   'color' => '#312CF8', 'weight' => 1,  'severityname' => 'Initial');
$severity[6] = array('style' => 'spurple', 'color' => '#E920CF', 'weight' => 1,  'severityname' => 'Strange');
$severity[7] = array('style' => 'syellow', 'color' => '#F1B10E', 'weight' => 3,  'severityname' => 'Minor');
$severity[8] = array('style' => 'sorange', 'color' => '#EE870D', 'weight' => 7,  'severityname' => 'Major');
$severity[9] = array('style' => 'sred',    'color' => '#FF0000', 'weight' => 15, 'severityname' => 'Critical');


$rollups[] = array('rollup' => 2,  'severity' => 7);
$rollups[] = array('rollup' => 5,  'severity' => 8);
$rollups[] = array('rollup' => 14, 'severity' => 9);

$newerfilesev = 4;

$totalseverity = 0;


//!!!!!!!!!!!!!// Do NOT edit anything below //!!!!!!!!!!!!!//
// Set some characters we will use for exploding the data streams
$explode = '*|*';
$break = "\n";

// Make an array to hold the direcory information
$directories = array('.' => array('localfiles' => 0, 'remotefiles' => 0, 'severity' => 0));

// Make an array to hold the local and, if applicable, remote file versioning information
$files = array();

if( !file_exists($local_file) )
{
	// Get the $directories and fill the array $directories
	if ($subdirectories)
	{
		GrabAllLocalDirectories('.');
	}
	// Get the $files / versioning info for each $directories and fill the array $files
	foreach ($directories as $directory => $filecount)
	{
		// Grab all local $files and store the information into the array $files
		GrabLocalVersions($directory);
	}
	file_writer( $local_file , json_encode($files) , $mode='wb' );
}
else
{
	$files = json_decode(file_get_contents($local_file), true);
}

// now we build our full file array
$files = array_overlay( $remote, $files );

foreach ($files as $directory => $filedata)
{
	$directories[$directory] = count($filedata);
}

/**
 * Grab all directories and subdirectories for directory $dir and shove them into the global array $directories
 *
 * @param string $dir
 */
function GrabAllLocalDirectories($dir)
{
	global $directories;

	if ($handle = @opendir($dir)) {
		while ($filename = readdir($handle))
		{
			$directory = $dir . '/' . $filename;
			if(is_dir($directory) && CheckDirectory($filename))
			{
				$directories[$directory] = array('localfiles' => 0, 'remotefiles' => 0, 'severity' => 0);
				GrabAllLocalDirectories($directory);
			}
		}
		closedir($handle);
	}
}

/**
 * This function will determine all version of all files in the current directory
 * and will fill the $versions array with this data
 *
 * @param string $directory
 */
function GrabLocalVersions($directory)
{
	global $directories;

	if ($handle = @opendir($directory))
	{
		while ($filename = readdir($handle))
		{
			if(isset($filename) && !is_dir($directory . '/' . $filename) && CheckExtension($filename))
			{
				// Increase the filecounter for this directory
				$directories[$directory]['localfiles']++;
				// Get the file versioning info and store it into the array
				GetFileVersionInfo($directory, $filename);
			}
		}
		closedir($handle);
	}
}

/**
 * Check the file against the $extension array
 *
 * @param string $filename
 * @return int
 */
function CheckExtension($filename)
{
	global $extensions, $ignored_files;

	$returnvalue = 0;
	if ($filename == '.' || $filename == '..' || $filename == 'version_match.php')
	{
		$returnvalue = 0;
	}
	else
	{
		// Check the extension
		$fileextension = pathinfo($filename, PATHINFO_EXTENSION);


		if (in_array($fileextension, $extensions) && !in_array($filename, $ignored_files))
		{
			$returnvalue = 1;
		}
		else
		{
			$returnvalue = 0;
		}
	}
	return $returnvalue;
}

/**
 * Check the file against the $extension array
 *
 * @param string $dirname
 * @return int
 */
function CheckDirectory($dirname)
{
	global $ignored_dirs;

	$returnvalue = 0;

	if (in_array($dirname, $ignored_dirs))
	{
		$returnvalue = 0;
	}
	else
	{
		$returnvalue = 1;
	}
	return $returnvalue;
}

/**
 * Grab all the versioning data regarding the $file inside the $directory
 *
 * @param string $directory
 * @param string $file
 */
function GetFileVersionInfo($directory, $file)
{
	global $files, $remote;

	$filefullpath = $directory . '/' . $file;
	

	if (check_if_image($file))
	{
		$d=file_get_contents($filefullpath);
		$s = filesize($filefullpath);//strlen( $encoded_image );
	}
	else
	{
		$d = str_replace("\r\n","\n",file_get_contents_utf8($filefullpath));
		$s = strlen( $d );
	}

	$files[$directory][$file]['update'] = '';
	$files[$directory][$file]['local']['versionFile'] = $file;
	$files[$directory][$file]['local']['versionMD5'] = _getsha($file,$filefullpath);//sha1_file($filefullpath);
	$files[$directory][$file]['local']['versionSize'] = $s;
	$files[$directory][$file]['local']['versionDesc'] = '';
	$files[$directory][$file]['local']['versionDate'] = '';
	$files[$directory][$file]['local']['versionAuthor'] = '';
	$files[$directory][$file]['local']['update'] = '';
}

function file_get_contents_utf8($fn) { 
     $content = file_get_contents($fn); 
      return mb_convert_encoding($content, 'UTF-8', 
          mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
} 

/**
 * Verify version info
 *
 */
function VerifyVersions()
{
	global $files, $directories, $problemsev, $severity, $rollups, $totalrollup, $totalseverity, $newerfilesev;

	// Process verification for all directories, Local and SVN
	foreach ($files as $directory => $filedata)
	{
		// Initialize the Directory severity
		$files[$directory]['severity'] = 0;
		// Initialize the File tooltip
		$files[$directory]['tooltip'] = '';
		$files[$directory]['newer'] = 0;
		$nosvn = 0;
		$nolocal = 0;



		foreach ($filedata as $filename => $file)
		{
			// Initialize the File severity
			$files[$directory][$filename]['severity'] = 0;
			// Initialize the File tooltip
			$files[$directory][$filename]['tooltip'] = '';
			$files[$directory][$filename]['rogue'] = 0;
			$files[$directory][$filename]['update'] = 0;
			$files[$directory][$filename]['missing'] = 0;
			$files[$directory][$filename]['diff'] = 0;
			$files[$directory][$filename]['newer'] = 0;

			// Check if Both Local and SVN files are present
			if (!empty($file['local']['versionMD5']) && !empty($file['remote']['versionMD5']))
			{
				// Check if the local version matches the SVN version
				if (version_compare($file['local']['versionSize'], $file['remote']['versionSize']) < 0)
				{
					$files[$directory][$filename]['severity'] += $severity[$problemsev['revisiongreater']]['weight'];
					$files[$directory][$filename]['newer'] = 1;
					$files[$directory][$filename]['tooltip'] .= 'Local Version: ' . $file['local']['versionSize'] . ' is HIGHER than SVN Version: ' . $file['remote']['versionSize'] . '<br />';
					$files[$directory][$filename]['rev'] = $file['local']['versionSize'] . ' < ' . $file['remote']['versionSize'];
					$files[$directory][$filename]['update'] = 1;
					$files[$directory][$filename]['diff'] = 1;
				}
				elseif (version_compare($file['local']['versionSize'], $file['remote']['versionSize']) > 0)
				{
					$files[$directory][$filename]['severity'] += $severity[$problemsev['revisiongreater']]['weight'];
					$files[$directory][$filename]['tooltip'] .= 'Local Version: ' . $file['local']['versionSize'] . ' is HIGHER than SVN Version: ' . $file['remote']['versionSize'] . '<br />';
					$files[$directory][$filename]['rev'] = $file['local']['versionSize'] . ' > ' . $file['remote']['versionSize'];
					$files[$directory][$filename]['diff'] = 1;
				}
				elseif (version_compare($file['local']['versionSize'], $file['remote']['versionSize']) == 0)
				{
					$files[$directory][$filename]['rev'] = $file['local']['versionSize'];
				}

				// Check if the local MD5 matches the SVN MD5
				if ($file['local']['versionMD5'] !=  $file['remote']['versionMD5'] && !empty($file['local']['versionMD5']) && !empty($file['remote']['versionMD5']))
				{
					if (check_if_image($filename))
					{
						$files[$directory][$filename]['severity'] += $severity[$problemsev['MD5binary']]['weight'];
						$files[$directory][$filename]['tooltip'] .= 'Local MD5 does not match with SVN<br />';
						$files[$directory][$filename]['update'] = 1;
					}
					else
					{
						$files[$directory][$filename]['severity'] += $severity[$problemsev['MD5']]['weight'];
						$files[$directory][$filename]['tooltip'] .= 'Local MD5 does not match with SVN<br />';
					}
					$files[$directory][$filename]['md5'] = 'MD5 String does NOT match';
					$files[$directory][$filename]['diff'] = 1;
				}
				else
				{
					$files[$directory][$filename]['md5'] = 'MD5 String Matches';
				}
			}
			elseif (!empty($file['local']['versionMD5']) && empty($file['remote']['versionMD5']))
			{
				$files[$directory][$filename]['severity'] += $severity[$problemsev['nosvn']]['weight'];
				$files[$directory][$filename]['tooltip'] .= 'Local file does not exist in SVN<br />';
				$files[$directory][$filename]['rogue'] = 1;
				//$files[$directory][$filename]['rev'] = $file['local']['versionSize'];
			}
			elseif (empty($file['local']['versionMD5']) && !empty($file['remote']['versionMD5']))
			{
				$files[$directory][$filename]['severity'] += $severity[$problemsev['nolocal']]['weight'];
				$files[$directory][$filename]['tooltip'] .= 'Local file is missing but is present in SVN<br />';
				$files[$directory][$filename]['update'] = 1;
				$files[$directory][$filename]['missing'] = 1;
			}
			else
			{
				$files[$directory][$filename]['severity'] += $severity[$problemsev['unknown']]['weight'];
				$files[$directory][$filename]['tooltip'] .= 'Unknown Issue<br />';
			}
			$files[$directory][$filename]['rollup'] = 0;
			foreach ($rollups as $rollupkey => $rollup)
			{
				if ($files[$directory][$filename]['severity'] > $rollup['rollup'])
				{
					$files[$directory][$filename]['rollup'] = $rollup['severity'];
				}
			}

			$files[$directory]['severity'] += $files[$directory][$filename]['severity'];

			if (!$files[$directory][$filename]['severity'] && $files[$directory][$filename]['newer'])
			{
				$files[$directory][$filename]['rollup'] = $newerfilesev;
				$files[$directory]['newer'] = 1;
			}

			if ($files[$directory][$filename]['tooltip'] == '' && !$files[$directory][$filename]['severity'] && !$files[$directory][$filename]['rogue'] && !$files[$directory][$filename]['diff'] && !$files[$directory][$filename]['newer'])
			{
				$files[$directory][$filename]['tooltip'] .= 'Local file same as SVN<br />';
			}

			if ($files[$directory][$filename]['rogue'])
			{
				$files[$directory][$filename]['rollup'] = $problemsev['nosvn'];
				$nosvn = 1;
			}

			if ($files[$directory][$filename]['missing'])
			{
				$files[$directory][$filename]['rollup'] = $problemsev['nolocal'];
				$nolocal = 1;
			}

			if ($files[$directory][$filename]['rollup'])
			{
				$files[$directory]['tooltip'] .= 'File: ' . $filename . ' - Severity: ' . $files[$directory][$filename]['rollup'] . '<br />';
			}
		}

		if ($files[$directory]['tooltip'] == '')
		{
			$files[$directory]['tooltip'] = 'No File Version Issues!';
		}

		$files[$directory]['rollup'] = 0;

		foreach ($rollups as $rollupkey => $rollup)
		{
			if ($files[$directory]['severity'] > $rollup['rollup'])
			{
				$files[$directory]['rollup'] = $rollup['severity'];
			}
		}

		if (!$files[$directory]['severity'])
		{
			if ($files[$directory]['newer'])
			{
				$files[$directory]['rollup'] = $newerfilesev;
			}

			if ($nosvn)
			{
				$files[$directory]['rollup'] = $problemsev['nosvn'];
			}

			if ($nolocal)
			{
				$files[$directory]['rollup'] = $problemsev['nolocal'];
			}
		}

		$totalseverity += $files[$directory]['severity'];
	}

	$totalrollup = 0;

	foreach ($rollups as $rollupkey => $rollup)
	{
		if ($files[$directory]['severity'] > $rollup['rollup'])
		{
			$totalrollup = $rollup['severity'];
		}
	}
}


/**
 * Gets the gd_info and formats the output
 *
 * @return string
 */
function describeGDdyn()
{
	$rowstripe = 1;

	if( function_exists('gd_info') )
	{
		$returnVal  = '<table class="border_frame" cellspacing="0" width="100%">' . "\n";
		$returnVal .= "\t<tr>\n\t\t<td class=\"membersRow" . (((++$rowstripe)%2)+1) . "\">GD Status</td>\n\t\t<td class=\"membersRowRight" . ((($rowstripe)%2)+1) . "\"><span class=\"green\">On</span></td>\n\t</tr>\n";

		$info = gd_info();
		$keys = array_keys($info);
		for($i=0; $i<count($keys); $i++)
		{
			if(is_bool($info[$keys[$i]]))
			{
				$returnVal .= "\t<tr>\n\t\t<td class=\"membersRow" . (((++$rowstripe)%2)+1) . "\">" . $keys[$i] . "</td>\n\t\t<td class=\"membersRowRight" . ((($rowstripe)%2)+1) . "\">" . yesNo($info[$keys[$i]]) . "</td>\n\t</tr>\n";
			}
			else
			{
				$returnVal .= "\t<tr>\n\t\t<td class=\"membersRow" . (((++$rowstripe)%2)+1) . "\">" . $keys[$i] . "</td>\n\t\t<td class=\"membersRowRight" . ((($rowstripe)%2)+1) . "\">" . $info[$keys[$i]] . "</td>\n\t</tr>\n";
			}
		}
		$returnVal .= "</table>\n";
	}
	else
	{
		$returnVal  = '<table cellspacing="0" width="100%">' . "\n";
		$returnVal .= "\t<tr>\n\t\t<td class=\"membersRow1\">GD Status</td>\n\t\t<td class=\"membersRowRight1\"><span class=\"red\">Off</span></td>\n\t</tr>\n";
		$returnVal .= "</table>\n";
	}

	return $returnVal;
}

/**
 * Show any config setup errors
 *
 * @return string
 */
function ConfigErrors()
{
	global $roster;

	// Get freetype installation status
	if ( function_exists('gd_info'))
	{
		$gdinfo = gd_info();
		$FreeType = $gdinfo['FreeType Support'];
	}
	else
	{
		$FreeType = 0;
	}

	// Start building error string
	$errors = '';

	// Check GD and Freetype status in PHP config if GD Realm Status option is set
	if ($roster->config['rs_display'] == 'image')
	{
		if( !function_exists('gd_info') )
		{
			$errors .= "Realm Status GD image mode enabled (RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;Display Mode = Image) in RosterCP
			but GD library was not found.<br />Either load the GD extension in PHP or set 
			(RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;Display Mode = text) in RosterCP<br />\n";
		}
		if ($FreeType == 0)
		{
			$errors .= "Realm Status GD image mode enabled (RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;Display Mode = Image) in RosterCP
			but FreeType support was not found.<br />Either load the Freetype extension in PHP or set 
			(RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;Display Mode = text) in RosterCP<br />\n";
		}
	}
	if ($roster->config['motd_display_mode'] == 1)
	{
		if( !function_exists('gd_info') )
		{
			$errors .= "MOTD GD image mode enabled (RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;MOTD Display Mode = on) in RosterCP
			but GD library was not found.<br />Either load the GD extension in PHP or set 
			(RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;MOTD Display Mode = text) in RosterCP<br />\n";
		}
		if ($FreeType == 0)
		{
			$errors .= "MOTD GD image mode enabled (RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;MOTD Display Mode = on) in RosterCP
			but FreeType support was not found.<br />Either load the Freetype extension in PHP or set 
			(RosterCP-&gt;Main Settings-&gt;Realmstatus-&gt;MOTD Display Mode = text) in RosterCP<br />\n";
		}
	}

	if( !empty($errors) )
	{
		$errors = '<span class="red">' . $errors . "</span><br /><br />\n";
	}

	return $errors;
}

/**
 * Determine a on/off value from a bool. true/1=on false/0=off
 *
 * @param bool $bool
 * @return formatted on/off string
 */
function onOff($bool)
{
	if( $bool )
	{
		return '<span class="green">On</span>';
	}
	else
	{
		return '<span class="red">Off</span>';
	}
}

/**
 * Determine a on/off value from a bool. true/1=on false/0=off
 *
 * @param bool $bool
 * @return formatted on/off string
 */
function onOffRev($bool)
{
	if( $bool )
	{
		return '<span class="red">On</span>';
	}
	else
	{
		return '<span class="green">Off</span>';
	}
}

/**
 * Determine a yes/no value from a bool. true/1=yes false/0=no
 *
 * @param bool $bool
 * @return formatted yes/no string
 */
function yesNo($bool)
{
	if($bool)
	{
		return '<span class="green">Yes</span>';
	}
	else
	{
		return '<span class="red">No</span>';
	}
}


/**
 * Writes a file from the SVN copy
 * (Not currently used)
 *
 * @param string $filename
 * @return bool
 */
function downloadsvn($filename)
{
	$file_source = ROSTER_SVNREMOTE . '?getfile=' . $filename . '&mode=full';

	$file_source = str_replace("\r\n","\n",urlgrabber($file_source));
	if ($file_source===false)
	{
		roster_die("[ERROR] Cannot Read File");
	}
	file_writer($filename,$file_source);
	return true;
}


function difffile($old,$new)
{
	// Clean up multiple line breaks
	$old = str_replace("\r\n","\n",$old);
	$new = str_replace("\r\n","\n",$new);

	// Split the source text into arrays of lines
	$t1 = explode("\n",$old);
	$x = array_pop($t1);
	if ($x > '')
	{
		$t1[] = "$x\n\\ No newline at end of file";
	}
	$t2 = explode("\n",$new);
	$x = array_pop($t2);
	if ($x>'')
	{
		$t2[] = "$x\n\\ No newline at end of file";
	}

	// Build a reverse-index array using the line as key and line number as value
	// Don't store blank lines, so they won't be targets of the shortest distance search
	foreach($t1 as $i=>$x)
	{
		if ($x > '')
		{
			$r1[$x][] = $i;
		}
	}
	foreach($t2 as $i=>$x)
	{
		if ($x > '')
		{
			$r2[$x][] = $i;
		}
	}

	// Start at beginning of each list
	$a1 = 0;
	$a2 = 0;
	$actions = array();

	// Walk this loop until we reach the end of one of the lists
	while ($a1 < count($t1) && $a2 < count($t2))
	{
		// If we have a common element, save it and go to the next
		if ($t1[$a1]==$t2[$a2])
		{
			$actions[] = 4;
			$a1++;
			$a2++;
			continue;
		}

		// Otherwise, find the shortest move (Manhattan-distance) from the current location
		$best1 = count($t1);
		$best2 = count($t2);
		$s1=$a1;
		$s2=$a2;
		while (($s1+$s2-$a1-$a2) < ($best1+$best2-$a1-$a2))
		{
			$d = -1;

			if(isset($s2) && isset($t2) && isset($t2[$s2]) && isset($r1) && isset($r1[$t2[$s2]]))
			{
				foreach((array)@$r1[$t2[$s2]] as $n)
				{
					if ($n>=$s1)
					{
						$d=$n;
						break;
					}
				}
			}
			if ($d>=$s1 && ($d+$s2-$a1-$a2) < ($best1+$best2-$a1-$a2))
			{
				$best1=$d; $best2=$s2;
			}
			$d = -1;

			if(isset($s1) && isset($t1) && isset($t1[$s1]) && isset($r2) && isset($r2[$t1[$s1]]))
			{
				foreach ((array)@$r2[$t1[$s1]] as $n)
				{
					if ($n >= $s2)
					{
						$d = $n;
						break;
					}
				}
			}
			if ($d>=$s2 && ($s1+$d-$a1-$a2) < ($best1+$best2-$a1-$a2))
			{
				$best1 = $s1;
				$best2 = $d;
			}
			$s1++;
			$s2++;
		}
		while ($a1<$best1)
		{
			$actions[] = 1;
			$a1++;
		}  // Deleted elements
		while ($a2<$best2)
		{
			$actions[] = 2;
			$a2++;
		}  // Added elements
	}

	// We've reached the end of one list, now walk to the end of the other
	while ($a1<count($t1))
	{
		$actions[] = 1;
		$a1++;
	}  // Deleted elements
	while ($a2<count($t2))
	{
		$actions[] = 2;
		$a2++;
	}  // Added elements

	// And this marks our ending point
	$actions[] = 8;

	// Now, let's follow the path we just took and report the added/deleted elements into $out.
	$op = 0;
	$x0 = $x1 = 0;
	$y0 = $y1 = 0;
	$out = array();
	$outcount = 0;
//	print_r($actions);
	foreach ($actions as $act)
	{
		if ($act == 1)
		{
			$op |= $act;
			$x1++;
			continue;
		}
		if ($act == 2)
		{
			$op |= $act;
			$y1++;
			continue;
		}
		if ($op > 0)
		{
			$xstr = ($x1 == ($x0+1)) ? $x1 : ($x0+1) . "," . $x1;
			$ystr = ($y1 == ($y0+1)) ? $y1 : ($y0+1) . "," . $y1;
			if ($op == 1)
			{
				$out[$outcount]['rownr1'] = $xstr;
				$out[$outcount]['rownr2'] = $y1;
				$out[$outcount]['action'] = 'Deleted';
				$out[$outcount]['color'] = 'red';
			}
			elseif ($op == 3)
			{
				$out[$outcount]['rownr1'] = $xstr;
				$out[$outcount]['rownr2'] = $ystr;
				$out[$outcount]['action'] = 'Changed';
				$out[$outcount]['color'] = 'blue';
			}
			$tmpi = 0;
			while ($x0 < $x1)
			{
				$out[$outcount]['from'][$tmpi] = $t1[$x0];
				$x0++;
				$tmpi++;
			}   // Deleted elems
			if ($op == 2)
			{
				$out[$outcount]['rownr1'] = $x1;
				$out[$outcount]['rownr2'] = $ystr;
				$out[$outcount]['action'] = 'Added';
				$out[$outcount]['color'] = 'green';
			}
			elseif ($op == 3)
			{
			}
			$tmpi = 0;
			while ($y0 < $y1)
			{
				$out[$outcount]['to'][$tmpi] = $t2[$y0];
				$y0++;
				$tmpi++;
			}   // Added elems
		}
		$x1++;
		$x0 = $x1;
		$y1++;
		$y0 = $y1;
		$op = 0;
		$outcount++;
	}

	return $out;
}

/**
 * Highlight php code
 *
 * @param string $string
 * @param int $startline
 * @return string
 */
function highlight_php($string, $startline=1)
{
	$lines = explode("\n",$string);

	$returnstring = '<div style="white-space:nowrap;overflow:auto;"><table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-style:solid;border-width:1px;border-color:white black black white">';

	foreach( $lines as $key => $line )
	{
		if( !empty($line) )
		{
			$line = "<?php x\n" . $line;

			$linecoded = highlight_string($line,true);

			$linecoded = str_replace(array('<font ', '</font>','<code>','</code>'), array('<span ', '</span>','',''), $linecoded);
			$linecoded = preg_replace('/color="(.*?)"/', 'style="color:\\1;"', $linecoded);

			$linecoded = str_replace('&lt;?php&nbsp;x<br />', '', $linecoded);

			if( !empty($linecoded) )
			{
				$returnstring .= '<tr>';
				$returnstring .= '  <td width="1%" valign="top" style="background-color:#33ccff;border-style:solid;border-width:1px;border-color:white;"><code>' . $startline . '</code></td>';
				$returnstring .= '  <td width="99%" valign="top" style="background-color:white;"><code>' . $linecoded . '</code></td>';
				$returnstring .= '</tr>';
			}
		}
		else
		{
			$returnstring .= '<tr>';
			$returnstring .= '  <td width="1%" valign="top" style="background-color:#33ccff;border-style:solid;border-width:1px;border-color:white;"><code>' . $startline . '</code></td>';
			$returnstring .= '  <td width="99%" valign="top" style="background-color:white;">&nbsp;</td>';
			$returnstring .= '</tr>';
		}

		$startline++;
	}
	$returnstring .= '</table></div>';

	return $returnstring;
}

/**
 * Check the timestamp
 *
 * @param sting $date
 * @param sting $time
 * @return sting
 */
function check_date_time($date, $time)
{
	if (preg_match("~(\d\d\d\d)[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])~", $date, $datepart))
	{
		$returndate = $datepart;
	}
	else
	{
		$returndate = array(0,1,1,1970);
	}


	if (preg_match("~([0-9]|0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])~", $time, $timepart))
	{
		$returntime = $timepart;
	}
	else
	{
		$returntime = array(0,0,0,0);
	}

	$returnunixdate = gmmktime($returntime[1], $returntime[2], $returntime[3], $returndate[2], $returndate[3], $returndate[1]);

	return $returnunixdate;
}

function _getsha($file,$filefullpath)
{
	if (check_if_image($file))
	{
		$d=file_get_contents($filefullpath);
		$s = filesize($filefullpath);//strlen( $encoded_image );
		$x = sha1("blob ".$s."\0" .$d);
	}
	else
	{
		$d = str_replace("\r\n","\n",file_get_contents_utf8($filefullpath));
		$s = strlen( $d );
		$x = sha1("blob " .$s. "\0" .$d);
	}
	
	return $x;
}