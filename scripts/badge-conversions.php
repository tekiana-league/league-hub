<?php
	function strtobadge($input)
	{
		$badgeBegin = '<div class="badge ';
		$badgeEnd = '"></div>';
		switch ($input)
		{
			case 'A':
				return $badgeBegin.'normal'.$badgeEnd;
				break;
			case 'B':
				return $badgeBegin.'fire'.$badgeEnd;
				break;
			case 'C':
				return $badgeBegin.'water'.$badgeEnd;
				break;
			case 'D':
				return $badgeBegin.'electric'.$badgeEnd;
				break;
			case 'E':
				return $badgeBegin.'grass'.$badgeEnd;
				break;
			case 'F':
				return $badgeBegin.'ice'.$badgeEnd;
				break;
			case 'G':
				return $badgeBegin.'fighting'.$badgeEnd;
				break;
			case 'H':
				return $badgeBegin.'poison'.$badgeEnd;
				break;
			case 'I':
				return $badgeBegin.'ground'.$badgeEnd;
				break;
			case 'J':
				return $badgeBegin.'flying'.$badgeEnd;
				break;
			case 'K':
				return $badgeBegin.'psychic'.$badgeEnd;
				break;
			case 'L':
				return $badgeBegin.'bug'.$badgeEnd;
				break;
			case 'M':
				return $badgeBegin.'rock'.$badgeEnd;
				break;
			case 'N':
				return $badgeBegin.'ghost'.$badgeEnd;
				break;
			case 'O':
				return $badgeBegin.'dragon'.$badgeEnd;
				break;
			case 'P':
				return $badgeBegin.'dark'.$badgeEnd;
				break;
			case 'Q':
				return $badgeBegin.'steel'.$badgeEnd;
				break;
			case 'R':
				return $badgeBegin.'fairy'.$badgeEnd;
				break;
			case 'a':
				return $badgeBegin.'normal taken'.$badgeEnd;
				break;
			case 'b':
				return $badgeBegin.'fire taken'.$badgeEnd;
				break;
			case 'c':
				return $badgeBegin.'water taken'.$badgeEnd;
				break;
			case 'd':
				return $badgeBegin.'electric taken'.$badgeEnd;
				break;
			case 'e':
				return $badgeBegin.'grass taken'.$badgeEnd;
				break;
			case 'f':
				return $badgeBegin.'ice taken'.$badgeEnd;
				break;
			case 'g':
				return $badgeBegin.'fighting taken'.$badgeEnd;
				break;
			case 'h':
				return $badgeBegin.'poison taken'.$badgeEnd;
				break;
			case 'i':
				return $badgeBegin.'ground taken'.$badgeEnd;
				break;
			case 'j':
				return $badgeBegin.'flying taken'.$badgeEnd;
				break;
			case 'k':
				return $badgeBegin.'psychic taken'.$badgeEnd;
				break;
			case 'l':
				return $badgeBegin.'bug taken'.$badgeEnd;
				break;
			case 'm':
				return $badgeBegin.'rock taken'.$badgeEnd;
				break;
			case 'n':
				return $badgeBegin.'ghost taken'.$badgeEnd;
				break;
			case 'o':
				return $badgeBegin.'dragon taken'.$badgeEnd;
				break;
			case 'p':
				return $badgeBegin.'dark taken'.$badgeEnd;
				break;
			case 'q':
				return $badgeBegin.'steel taken'.$badgeEnd;
				break;
			case 'r':
				return $badgeBegin.'fairy taken'.$badgeEnd;
				break;
			default:
				return '';
				break;
		}
	}
	
	function role_strtobadge($input)
	{
		$badgeBegin = '<div class="card-front-badge ';
		$badgeEnd = '"></div>';
		switch ($input)
		{
			case '3':
				return $badgeBegin.'normal'.$badgeEnd;
				break;
			case '4':
				return $badgeBegin.'fire'.$badgeEnd;
				break;
			case '5':
				return $badgeBegin.'water'.$badgeEnd;
				break;
			case '6':
				return $badgeBegin.'electric'.$badgeEnd;
				break;
			case '7':
				return $badgeBegin.'grass'.$badgeEnd;
				break;
			case '8':
				return $badgeBegin.'ice'.$badgeEnd;
				break;
			case '9':
				return $badgeBegin.'fighting'.$badgeEnd;
				break;
			case '10':
				return $badgeBegin.'poison'.$badgeEnd;
				break;
			case '11':
				return $badgeBegin.'ground'.$badgeEnd;
				break;
			case '12':
				return $badgeBegin.'flying'.$badgeEnd;
				break;
			case '13':
				return $badgeBegin.'psychic'.$badgeEnd;
				break;
			case '14':
				return $badgeBegin.'bug'.$badgeEnd;
				break;
			case '15':
				return $badgeBegin.'rock'.$badgeEnd;
				break;
			case '16':
				return $badgeBegin.'ghost'.$badgeEnd;
				break;
			case '17':
				return $badgeBegin.'dragon'.$badgeEnd;
				break;
			case '18':
				return $badgeBegin.'dark'.$badgeEnd;
				break;
			case '19':
				return $badgeBegin.'steel'.$badgeEnd;
				break;
			case '20':
				return $badgeBegin.'fairy'.$badgeEnd;
				break;
			default:
				return '';
				break;
		}
	}
	
	function roletochar($input)
	{
		switch ($input)
		{
			case '3':
				return 'A';
				break;
			case '4':
				return 'B';
				break;
			case '5':
				return 'C';
				break;
			case '6':
				return 'D';
				break;
			case '7':
				return 'E';
				break;
			case '8':
				return 'F';
				break;
			case '9':
				return 'G';
				break;
			case '10':
				return 'H';
				break;
			case '11':
				return 'I';
				break;
			case '12':
				return 'J';
				break;
			case '13':
				return 'K';
				break;
			case '14':
				return 'L';
				break;
			case '15':
				return 'M';
				break;
			case '16':
				return 'N';
				break;
			case '17':
				return 'O';
				break;
			case '18':
				return 'P';
				break;
			case '19':
				return 'Q';
				break;
			case '20':
				return 'R';
				break;
			default:
				return '';
				break;
		}
	}
?>