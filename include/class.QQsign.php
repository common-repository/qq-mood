<?php
class QQsign {
	/**
	 * 变量集散地
	 * @var unknown_type
	 */
	public $url = "http://taotao.qq.com/cgi-bin/emotion_cgi_msglist";
	public $params = Array ();
	public $timeout = 1800;
	public $data;
	private $contents;
	
	/**
	 * 构造函数
	 */
	function __construct($qq_number, $timeout = 1800) {
		if (isset ( $qq_number )) {
			$this->params ['uin'] = $qq_number;
			$this->params ['ftype'] = 0;
			$this->params ['sort'] = 1;
			$this->timeout = $timeout;
		} else {
			echo "QQ mood插件需要您设置QQ号码\n";
		}
	}
	
	/**
	 * 转化成url参数
	 * @param {Array} $params 参数数组对象
	 */
	function paramsToString($params) {
		$str = Array ();
		foreach ( $params as $key => $value ) {
			$str [] = $key . "=" . $value;
		}
		return implode ( "&", $str );
	}
	
	/**
	 * 获取签名
	 * @param {Number} $pos 起始页码
	 * @param {Number} $num QQ心情条数
	 */
	public function getSign($pos = 1, $num = 20) {
		$this->params ['pos'] = $pos;
		$this->params ['num'] = $num;
		$this->url = $this->url . "?" . $this->paramsToString ( $this->params );
		$this->cache ( $pos, $num );
	}
	
	/**
	 * 抓取没标页面
	 */
	public function getPage() {
		$this->contents = file_get_contents ( $this->url );
	}
	
	/**
	 * 获取心情数据
	 */
	public function getData() {
		preg_match_all ( "/<span id=\"fw_c[^>]+>([^<]*?)<\/span>[\s|\S]*?<span class=\"c_tx3\">([^<]*?)<\/span>/", $this->contents, $this->tSign );
	}
	
	/**
	 * 整理数据
	 */
	public function makeArray() {
		$sign = $this->tSign [1];
		$time = $this->tSign [2];
		$count = count ( $sign );
		$str = Array ();
		for($i = 0; $i < $count; $i ++) {
			$str [$i] [0] = $sign [$i];
			$str [$i] [1] = $time [$i];
		}
		$str = json_encode ( $str );
		$order = array ("\\t", "\\n", "\\r" );
		$this->data = str_replace ( $order, '', $str );
	}
	
	/**
	 * 使用缓存策略
	 * @param {Number} $pos 起始页码
	 * @param {Number} $num QQ心情条数	 * 
	 */
	public function cache($pos, $num) {
		$file_name = dirname(__FILE__). "/SignCache".$this->params ['uin']."_" . $num . "_" . $pos . ".php";
		if (file_exists ( $file_name )) {
			include_once ($file_name);
		}
		$this->cacheToFile ( $file_name );
	}
	
	/**
	 * 根据配置生成缓存文件
	 * @parma {String} $file_name 缓存文件名称
	 */
	public function cacheToFile($file_name) {
		$cacheFile = "";
		if (empty ( $this->cacheCreateDate ) || (time () - intval ( $this->cacheCreateDate ) > $this->timeout)) {
			$this->getPage ();
			$this->getData ();
			$this->makeArray ();
			if (is_writeable ( dirname(__FILE__) )) {
				$cacheFile .= "<?php\n";
				$cacheFile .= "/**\n";
				$cacheFile .= "+----------------------------------+\n";
				$cacheFile .= "|  Powered By webbeast.cn          |\n";
				$cacheFile .= "+----------------------------------+\n";
				$cacheFile .= "|  Cache file                      |\n";
				$cacheFile .= "+----------------------------------+\n";
				$cacheFile .= "*/\n";
				$cacheFile .= "\$this->cacheCreateDate = " . time () . ";\n";
				$cacheFile .= "\$this->data ='" . $this->data . "';";
				$cacheFile .= "\n?>\n";
				$fp = fopen ( $file_name, "wb+" );
				fwrite ( $fp, $cacheFile );
				fclose ( $fp );
			}
		}
	}
}   