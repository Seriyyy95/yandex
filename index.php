<?php
require_once 'Yandex.php';

// get "query" and "page" from request
$query = isset($_REQUEST['query'])?$_REQUEST['query']:'';
$page  = isset($_REQUEST['page']) ?$_REQUEST['page']:0;
$host  = isset($_REQUEST['host']) ?$_REQUEST['host']:null;


if ($query) {
    // Create new instance of Yandex class
    $Yandex = new Yandex();
    
    // Set Query
    $Yandex -> query($query)
            -> host($host)                      // set host
            -> page($page)                      // set current page
            -> limit(10)                        // set page limit
            -> set('max-title-length',   160)   // set some options
            -> set('max-passage-length', 200)
            -> request()                        // send request
            ;
}

// current URL
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = substr($url, 0, strpos($url, '?')) .'?query='.urlencode($query).'&host='.urlencode($host);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Yandex XML Search</title>
    <meta name="keywords" content="Yandex XML, PHP, PHP5" />
    <meta name="description" content="Yandex XML Search for PHP" />    
    <link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
<div class="body">
    <div class="form">    
        <form>
            Яндекс, <input type="text" name="query" class="txt" value="<?php echo $query;?>"/>, 
            <input type="submit" class="smb" name="search" value="Ищи!"/>    
        </form>
    </div>
    <div class="download">
        Демонстрация работы PHP скрипта с поисковым сервисом Яндекс.XML.<br/>
        Последняя версия всегда доступна на страницах Code Google:<br/>
        <code>
        <a href="http://code.google.com/p/yandex/downloads/list">http://code.google.com/p/yandex/downloads/list</a>
        </code>
    
        Доступ к SVN репозиторию проекта:<br/>
        <code>
        svn checkout http://yandex.googlecode.com/svn/trunk/ yandex-read-only
        </code>
    </div>
    <div class="data">
        <?php 
            // if $Yandex exists and don't have errors in response
            if (isset($Yandex) && empty($Yandex->error)) : 
        ?>
            Найдено: <?php echo $Yandex->total() .' ';
            switch (true) {
                case ($Yandex->total()%100 >= 5 && $Yandex->total()%100 < 20):
                    echo 'страниц';
                    break;
                case ($Yandex->total()%10 == 1):
                    echo 'страница';
                    break;
                case ($Yandex->total()%10 < 5):
                    echo 'страницы';
                    break;
                default:
                    echo 'страниц';
                    break;
            }
            ?> 
            <ol start="<?php echo $Yandex->getLimit()*$Yandex->getPage() + 1;?>">
            <?php foreach ($Yandex->result->response->results->grouping->group as $group) :?>
                <li><a href="<?php echo $group->doc->url; ?>" title="<?php echo $group->doc->url; ?>" ><?php Yandex::highlight($group->doc->title); ?></a>
                    <?php if (isset($group->doc->passages->passage)) : ?>
                    <ul>
                        <?php foreach ($group->doc->passages->passage as $passage) :?>
                        <li><?php Yandex::highlight($passage);?></li>                    
                        <?php endforeach;?>
                    </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach;?>
            </ol>
            
            <?php foreach ($Yandex->pageBar() as $page => $value) : ;?>
                <?php // switch statement for $value['type']
                switch ($value['type']) {
                	case 'link':
                		echo '<a href="'. $url .'&page='. $page .'" title="Page '. ($page+1) .'">'. sprintf($value['text'], $page+1) .'</a> | ';
                		break;
                	case 'current':
                		echo sprintf($value['text'], $page+1) .' | ';
                		break;
                	case 'text':
                		echo $value['text'] .' | ';
                		break;
                
                	default:
                		break;
                }
                ?>
            <?php endforeach;?>
            <?php if (($Yandex->getPage() < $Yandex->pages()) && 
                      ($Yandex->pages() > 1)) : ?>
                .. |
                <a href="<?php echo $url;?>&page=<?php echo $Yandex->getPage()+1;?>" title="Next Page">&raquo;</a>
            <?php endif; ?>
        <?php 
            // Error in response
            elseif(isset($Yandex) && isset($Yandex->error)):
        ?>
            <div class="error">Возникла ошибка: <?php echo $Yandex->error; ?></div>
        <?php endif; ?>
    </div>
    <div class="copyright">
        &copy; 2008 <a href="http://anton.shevchuk.name" title="Anton Shevchuk">Anton Shevchuk</a><br/>
        Поиск реализован на основе <a href="http://xml.yandex.ru/" title="Яндекс.XML">Яндекс.XML</a>
    </div>
</div>
</body>
</html>