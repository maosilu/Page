<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8">
</head>
<style>
	div.page {
		text-align: center;
	}
	div.page a{
		border: #aaaadd 1px solid; 
		text-decoration:none; 
		padding: 2px 5px 2px 5px;
		margin: 2px;
	}
	div.page span.current{
		border: #000099 1px solid;
		background-color: #000099;
		padding: 4px 6px 4px 6px;
		margin: 2px;
		color: #fff;
		font-weight: bold;
	}
	div.page span.disable{
		border: #eee 1px solid;
		padding: 2px 5px 2px 5px;
		margin: 2px;
		color: #ddd;
	}
	div.page form{
		display: inline;
	}
</style>
<body>
<?php
/** 1.传入页码*/
$page = $_GET['page'];

/** 2.取数据*/
$pageSize = 10; //每页显示的条数
$pre = $page-1; //上一页
$next = $page+1; //下一页
$show_page = 5; //显示的页码数
$pageoffset = ($show_page-1)/2; //页码的偏移量

//连接数据库
$link = mysqli_connect('localhost', 'root', '123456');
if(!$link){
	die('数据库链接失败！');
}
mysqli_select_db($link, 'test');
mysqli_set_charset($link, 'utf8');
//获取分页数据
$sql = "SELECT * FROM page LIMIT ".($page-1)*$pageSize.', '.$pageSize;
$result = mysqli_query($link, $sql);

echo "<div class='content'>";
echo "<table border=1 cellspacing=0 width=40% align='center'>";
echo "<tr><th>id</th>
<th>name</th></tr>";
while($row = mysqli_fetch_assoc($result)){
	echo "<tr>";
	echo "<td>{$row['id']}</td>";
	echo "<td>{$row['name']}</td>";
	echo "</tr>";
}
echo "</table>";
echo "</div>";

//获取数据总数
$total_sql = "SELECT * FROM page";
$total_rows = mysqli_num_rows(mysqli_query($link, $total_sql));
$total_pages = ceil($total_rows/$pageSize);

mysqli_free_result($result);
mysqli_close($link);

/** 3.显示数据 + 页码*/
$start = 1; //初始化开始显示页码
$end = $total_pages; //初始化结束显示页码

$page_banner = "<div class='page'>";
if($page > 1){
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=1'>首页</a>";
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page={$pre}'><上一页</a>";
}else{
	$page_banner .= "<span class='disable'>首页</span>";
	$page_banner .= "<span class='disable'><上一页</span>";
}

if($total_pages > $show_page){
	if($page > $pageoffset+1){
		$page_banner .= '...';
	}

	if($page > $pageoffset){
		$start = $page-$pageoffset;
		$end = $total_pages > $page+$pageoffset ? $page+$pageoffset : $total_pages;
	}else{
		$end = $show_page;
	}

	if($page+$pageoffset > $total_pages){
		$start = $start-($page+$pageoffset-$total_pages);
	}

	for($i=$start; $i<=$end; $i++){
		if($page == $i){
			$page_banner .= "<span class='current'>{$i}</span>";
		}else{
			$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page={$i}'>{$i}</a>";
		}
	}

	if($page+$pageoffset < $total_pages){
		$page_banner .= '...';
	}
}


if($page < $total_pages){
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page={$next}'>下一页></a>";
	$page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page={$total_pages}'>尾页></a>";
}else{
	$page_banner .= "<span class='disable'>下一页></span>";
	$page_banner .= "<span class='disable'>尾页</span>";
}


$page_banner .= "共{$total_pages}页，";
$page_banner .= "<form action='".$_SERVER['PHP_SELF']."' method='get'>
到第<input type='text' size='2' name='page'/>页，
<input type='submit' value='确定'/>
</form>";
$page_banner .= "</div>";

echo $page_banner;
?>
</body>
</html>