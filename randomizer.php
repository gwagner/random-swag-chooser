<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gwagner
 * Date: 3/7/13
 * Time: 7:31 PM
 * To change this template use File | Settings | File Templates.
 */

$nameLocation = "/tmp/users.json";

if(isset($_POST) && isset($_GET['add_name']))
{
    $content = array();
    if(file_exists($nameLocation))
        $content = json_decode(file_get_contents($nameLocation), true);

    $content[] = $_POST['name'];

    file_put_contents($nameLocation, json_encode($content));

    header('Location: randomizer.php');
    exit;
}

if(isset($_POST) && isset($_GET['get_name']))
{
    $content = json_decode(file_get_contents($nameLocation), true);

    shuffle($content);

    $content = array_values($content);

    $selection = mt_rand(0, count($content) - 1);

    $name = $content[$selection];

    unset($content[$selection]);

    file_put_contents($nameLocation, json_encode($content));

    echo '<h1>WINNER: '.$name.'</h1>';
}

?>

<h1>https://github.com/gwagner/random-swag-chooser</h1>

<h2>Randomizer</h2>


<form method="post" action="/randomizer.php?get_name=true">
    <input type="submit" value="Random" id="random" name="random" />
</form>


<form method="post" action="/randomizer.php?add_name=true">
    <input type="text" name="name" id="name" /><br />
    <input type="submit" value="Add Name" id="add_name" name="add_name" />
</form>

<?php
if(file_exists($nameLocation))
{

    $names = json_decode(file_get_contents($nameLocation), true);

    usort($names,
        function($a, $b){
            $i = 1;
            for($i = 0; $i < 10; $i++)
            {
                if(strtolower(substr($a, 0, $i+1)) == strtolower(substr($b, 0, $i+1)))
                    continue;

                return strtolower(substr($a, 0, $i+1)) < strtolower(substr($b, 0, $i+1)) ? -1 : 1;
            }

        }
    );

    $names = array_values($names);

    $open = '<div style="display: block; float: left; width: 300px; font-size: 1.5em">';
    $close = '</div>';

    foreach($names as $index => $name)
        echo $open.$name.$close;
}