<?php
namespace App\Tools;

class Upload{
	
	public static $fileType = [
		'img' => [ 
			'image/bmp' => 'bmp',
	        'image/x-ms-bmp' => 'bmp',
	        'image/cgm' => 'cgm',
	        'image/g3fax' => 'g3',
	        'image/gif' => 'gif',
	        'image/ief' => 'ief',
	        'image/jpeg' => 'jpeg',
	        'image/ktx' => 'ktx',
	        'image/png' => 'png',
	        'image/prs.btif' => 'btif',
	        'image/sgi' => 'sgi',
	        'image/svg+xml' => 'svg',
	        'image/tiff' => 'tiff',
	        'image/vnd.adobe.photoshop' => 'psd',
	        'image/vnd.dece.graphic' => 'uvi',
	        'image/vnd.dvb.subtitle' => 'sub',
	        'image/vnd.djvu' => 'djvu',
	        'image/vnd.dwg' => 'dwg',
	        'image/vnd.dxf' => 'dxf',
	        'image/vnd.fastbidsheet' => 'fbs',
	        'image/vnd.fpx' => 'fpx',
	        'image/vnd.fst' => 'fst',
	        'image/vnd.fujixerox.edmics-mmr' => 'mmr',
	        'image/vnd.fujixerox.edmics-rlc' => 'rlc',
	        'image/vnd.ms-modi' => 'mdi',
	        'image/vnd.ms-photo' => 'wdp',
	        'image/vnd.net-fpx' => 'npx',
	        'image/vnd.wap.wbmp' => 'wbmp',
	        'image/vnd.xiff' => 'xif',
	        'image/webp' => 'webp',
	        'image/x-3ds' => '3ds',
	        'image/x-cmu-raster' => 'ras',
	        'image/x-cmx' => 'cmx',
	        'image/x-freehand' => 'fh',
	        'image/x-icon' => 'ico',
	        'image/x-mrsid-image' => 'sid',
	        'image/x-pcx' => 'pcx',
	        'image/x-pict' => 'pic',
	        'image/x-portable-anymap' => 'pnm',
	        'image/x-portable-bitmap' => 'pbm',
	        'image/x-portable-graymap' => 'pgm',
	        'image/x-portable-pixmap' => 'ppm',
	        'image/x-rgb' => 'rgb',
	        'image/x-tga' => 'tga',
	        'image/x-xbitmap' => 'xbm',
	        'image/x-xpixmap' => 'xpm',
	        'image/x-xwindowdump' => 'xwd'],
	];
    
    public static $fileDefaultType = [   
        'img' => [
            'png', 'jpg', 'gif', 'jpeg'
        ],
        'excel' => [
            'csv', 'xlsx', 'xls'
        ]
    ];
    
    public static $fileSize = [
		'img' => 1024000,      
        'excel' => 52428800//10240000
	];
	
    public static $fileImportType = [   
        'single' => [
            'xlsx', 'xls'
        ],
        'batch' => [
            'csv'
        ]
    ];
    
	public static function checkType($subType,$type = 'img'){
		return isset(self::$fileType[$type]) && isset(self::$fileType[$type][$subType]) ? true : false;
	}
    
    /**
     * 获取文件类型
     * @param type $type
     * @return type
     */
    public static function getDefaultType($type = 'excel'){
		return isset(self::$fileDefaultType[$type]) && isset(self::$fileDefaultType[$type]) ? self::$fileDefaultType[$type] : [];
	}
    
    /**
     * 获取文件大小
     * @param type $type
     * @return type
     */
    public static function getSize($type = 'excel'){
		return isset(self::$fileSize[$type]) && isset(self::$fileSize[$type]) ? self::$fileSize[$type] : 1024000;
	}
	
    /**
     * 获取导入文件类型，区分是否批次导入
     * @param type $type
     * @return type
     */
    public static function getImportType($type = 'batch'){
		return isset(self::$fileImportType[$type]) && isset(self::$fileImportType[$type]) ? self::$fileImportType[$type] : [];
	}
}