<?php
    /*
    * @version      1.0.3 01.11.2018
    * @author       MAXXmarketing GmbH
    * @package      addon_api
    * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
    * @license      GNU/GPL
    */
    defined('_JEXEC') or die;

    $addon                   = $this->addon;
    $author                  = $this->author;
    $author_email            = $this->author_email;
    $author_url              = $this->author_url;
    $css                     = $this->css;
    $date                    = $this->date;
    $js                      = $this->js;
    $reports                 = $this->reports;
    $version                 = $this->version;
    $version_history         = $this->version_history;
    $sections_and_tasks      = $this->sections_and_tasks;
    $sections_and_tasks_menu = $this->sections_and_tasks_menu;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>JoomShopping API Documentation</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALR69MC0evbgtHr3jLR69+S0evfktHr3yLR69wi0evSQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAtHr0ELR69lC0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69vAAAAAAAAAAAAAAAAAAAAAAtHr0ELR69xS0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3kAAAAAAAAAAAAAAAALR69iy0evf8tHr3QLR69hy0eveUtHr3tLR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evb4AAAAALR69Ji0evf8tHr3/LR69nwAAAAAAAAAALR69Ai0evU4tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69JS0eva0tHr3/LR69/y0evf0tHr0FAAAAAAAAAAAAAAAALR69fi0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evcMtHr3aLR69/y0evf8tHr37LR69EAAAAAAAAAAALR69Gi0evR4tHr3+LR69/y0evf8tHr3/LR69/y0evf8tHr3yLR69+S0evf8tHr3/LR69/y0evZQAAAAALR69Ei0evfstHr3sLR69zS0evXEtHr3/LR69/y0evf8tHr3/LR69+S0evfktHr3/LR69/y0evf8tHr3/LR69vy0evXEtHr3JLR696y0evQ8AAAAALR69Ti0evf8tHr3/LR69/y0evfktHr3mLR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69zi0evQ8AAAAAAAAAAC0evQktHr3zLR69/y0evf8tHr3jLR69oi0evf8tHr3/LR69/y0evf8tHr3/LR69/y0eva0AAAAAAAAAAAAAAAAAAAAALR696C0evf8tHr3/LR69uS0evRUtHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69li0evRQtHr0EAAAAAC0evaYtHr3/LR69/y0evS4AAAAALR69oC0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evbktHr3cLR69/y0evZIAAAAAAAAAAAAAAAAtHr3QLR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evcUtHr0EAAAAAAAAAAAAAAAAAAAAAC0evaEtHr3/LR69/y0evf8tHr3/LR69/y0evf8tHr3/LR69/y0evYstHr0EAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALR69Fi0evaEtHr3mLR69+S0evfgtHr3aLR69rC0evSgAAAAAAAAAAAAAAAAAAAAA+B8AAOAHAADAAwAAgAEAAI8BAAAPgAAAD4AAAAYgAAACcAAAAPAAAADwAACAcQAAgAEAAMADAADgBwAA+B8AAA==" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <style>
            <?php echo $css; ?>
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
            <?php echo $js; ?>
        </script>
    </head>
    <body>
        <header>
            <h1 class="column">
                <img src="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTExIDc5LjE1ODMyNSwgMjAxNS8wOS8xMC0wMToxMDoyMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkM3MThDQzMxQjQwRDExRTc5QTM3QkE4QUYxQUY0QjRGIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkM3MThDQzMyQjQwRDExRTc5QTM3QkE4QUYxQUY0QjRGIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QzcxOENDMkZCNDBEMTFFNzlBMzdCQThBRjFBRjRCNEYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QzcxOENDMzBCNDBEMTFFNzlBMzdCQThBRjFBRjRCNEYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7DA5qVAAAD1klEQVR42ryZaUgUYRjH13HNxKKT0g5Jii4x0w9JVlAfsjuMsouKioooIuywi4IgBDssMogM+hCkVtBlUikWUdFhht2RH7oPUzKzsEOr/wP/gWGbcd7ZnfWBHzvuPDv73/d9n+N9DSmLiff4YZ3BIDAYJIMhoAcI4/0mUA0eg1vgCal2+kVeh/7ya6aACWCkja8ITgTz+Pc9UAyKQLnbAnuCtWAa6OPxz5LIMnAO5IBndh/SFB48E1wGGQGIM1p3sBSU8tVvgW1BFigE/T3uWy+QRzo4FSgfOAQ2gRBPcE1G8ThHVklgBNgPFnhaz8aBo8wOtgK3tLI43VLBPt8Z8xU4HWwOoohX4C34AZpN7s8Hq6wERoNsl9fcX8O1pJYxHKlFIBPUW8xgvFkeXA/6uiTsHdjB0Qrle1fBF14/ZRSvNongbmAjl1mzLjCO+c4N+wgWgxIbv58UHGNyTwpCCrimT/FUVotA7RNLW4mCbw14bXEvgvEQqjG0J7kg7gOnpUzRf65NPZ8h9Vzj9Ka4IFBK1yVF3yhmi4429T9OBA50KXLHglGKvu0U+4DxGvs5N0zSVAEYoeArAfJLwS9ZBCa4IO4guMhpKVDoFaUR6aTw3C4aG0un1gAqOQqS71aAl7zXmx2Qlcg2YJ1FevlvKXj96Kolzy0Bd0E/Cs1idTAucOlQZoHrPpUrh02rioVqDsXVMM8Vc39xAwwFG0C4SctfaAgcmdZcsNJJqdQsirZVoZ9jkufK2Tf+NrxXBa4wcPLBGnCES8FRLfdyyqJtHGWXts0iCcs63MnrbP6QySAWDGfN3eNn8H33cmuYaOcIntv47AJ14A5nJctk2p1ancZ9q52FKHyZtFaHOYL53MEFWgAqNLY+dhaumPnFGsEjl5J/scYprrRxvAAeOmhS27sgrlZ+qMZ0UdSC4332d7UKD5Xn7WWrFKidAW/0aTvbgoAGnxTS0jLIZSrRAhQnWeOEvOoPqgCnLJwHsCXztTCK8jIp5/iR56zsvF6BjGVuNzvrKN9ywzUVyXQjNhss5Eg1cdOd5JK4eubVRl+BVUzGeSZHbfLeV0Z8DddkZJC2pgfATavTLcljw9gMGC2Wrwme4JoE63a7k4VMB627mybd0XLfgDQTWMf1VdqK4m5z2/te9XRLGoh0HugE2yTFpYEXTo7f9GiSkczgltJt+8xuPJ0D4nEqUC9bcuI0ERxj0g7UZMN0mqcHW+2KgGq7X8lOejQfnKa4pzCaXlJPsq9UapRD/Pw3RAyrSyq3rV0Ne11psf6Abwy4Cu74HvCoo9nJF/0TYABw2cxy3P5/jQAAAABJRU5ErkJggg==" />
                <span>JoomShopping API Documentation</span>
            </h1>
        </header>
		<main class="column">
            <div>
                <menu>
                    <ul>
                        <li>
                            <a href="#summary" title="Summary">Summary</a>
                        </li>
                        <li>
                            <a href="#introduction" title="Introduction">Introduction</a>
                        </li>
                        <li>
                            <a href="#technical_requirements" title="Technical requirements">Technical requirements</a>
                        </li>
                        <li>
                            <a href="#version_history" title="Version history">Version history</a>
                        </li>
                        <li>
                            <a href="#request" title="Request">Request</a>
                            <ul>
                                <li>
                                    <a href="#authorization_data" title="Authorization data">Authorization data</a>
                                </li>
                                <li>
                                    <a href="#request_parameters" title="Request parameters">Request parameters</a>
                                </li>
                                <li>
                                    <a href="#connection" title="Connection">Connection</a>
                                </li>
                                <li>
                                    <a href="#disconnection" title="Disconnection">Disconnection</a>
                                </li>
                                <li>
                                    <a href="#example" title="Example">Example</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#sections_and_tasks" title="Sections and tasks">Sections and tasks</a>
                            <ul>
                                <?php echo $sections_and_tasks_menu; ?>
                            </ul>
                        </li>
                        <li>
                            <a href="#reply" title="Reply">Reply</a>
                            <ul>
                                <li>
                                    <a href="#formats" title="Formats">Formats</a>
                                </li>
                                <li>
                                    <a href="#reports" title="Reports">Reports</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </menu>
            </div><!--
         --><div>
                <section id="summary">
                    <header>Summary</header>
                    <div>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="variable">Version</td>
                                    <td>
                                        <a href="#version_history_<?php echo $version; ?>">
                                            <?php echo $version; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="variable">Date</td>
                                    <td>
                                        <?php echo $date; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="variable">Author</td>
                                    <td>MAXXmarketing GmbH</td>
                                </tr>
                                <tr>
                                    <td class="variable">Author's email</td>
                                    <td>
                                        <a href="mailto: <?php echo $author_email; ?>">
                                            <?php echo $author_email; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="variable">Author's website</td>
                                    <td>
                                        <a href="<?php echo $author_url; ?>" target="_blank">
                                            <?php echo $author_url; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="variable">License</td>
                                    <td>
                                        GNU/GPL
                                    </td>
                                </tr>
                                <tr>
                                    <td class="variable">Copyright</td>
                                    <td>
                                        Copyright (C) 2010 <a href="https://www.webdesigner-profi.de" target="_blank">webdesigner-profi.de</a>. All rights reserved
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                <section id="introduction">
                    <header>Introduction</header>
                    <div>
                        <p>The JoomShopping API is the RESTful system that allows to use JoomShopping capabilities. Before using the API need to get authorization data (email, password) of an API user from the administrator of a site, where the API is installed. All code snippets are on PHP.</p>
                    </div>
                </section>
                <section id="technical_requirements">
                    <header>Technical requirements</header>
                    <div>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="variable">PHP</td>
                                    <td>7.0+</td>
                                </tr>
                                <tr>
                                    <td class="variable">MySQL</td>
                                    <td>5.5.3+</td>
                                </tr>
                                <tr>
                                    <td>
                                        or <span class="variable">SQL Server</span>
                                    </td>
                                    <td>10.50.1600.1+</td>
                                </tr>
                                <tr>
                                    <td>
                                        or <span class="variable">PostgreSQL</span>
                                    </td>
                                    <td>9.1+</td>
                                </tr>
                                <tr>
                                    <td class="variable">Joomla!</td>
                                    <td>3.8.0+</td>
                                </tr>
                                <tr>
                                    <td class="variable">JoomShopping</td>
                                    <td>4.16.3+</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                <section id="version_history">
                    <header>Version history</header>
                    <div>
                        <ul>
                            <?php echo $version_history; ?>
                        </ul>
                    </div>
                </section>
                <section id="request">
                    <header>Request</header>
                    <div>
                        <section>
                            <div>
                                <p>Requests to the API are performing through <span class="variable">POST</span> request method with help of <span class="variable">cURL</span> or another same software. Here is a structure of a request:</p>
                                <code>$curl = curl_init('<span class="variable">%site_url%</span>/index.php?option=com_jshopping&controller=addon_api');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    '<span class="variable">%authorization_header%</span>'
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    '<span class="variable">%arg1_name%</span>' => '<span class="variable">%arg1_value%</span>',
    '<span class="variable">%arg2_name%</span>' => '<span class="variable">%arg2_value%</span>',
    '<span class="variable">%arg3_name%</span>' => '<span class="variable">%arg3_value%</span>',
    ...
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($curl);
curl_close($curl);
if ($res !== false) {
    $res = json_decode($res, true);
}
exit(var_dump($res));</code>
                                <p>
                                    Where <span class="variable">%site_url%</span> is the base URL of a website where the API is installed, <a href="https://www.example.com" target="_blank">https://www.example.com</a> for example.
                                </p>
                                <p>
                                    <span class="variable">%authorization_header%</span> is the <a href="#authorization_data">authorization data</a>, sent in headers.
                                </p>
                                <p>
                                    <span class="variable">%argN_name%</span> and <span class="variable">%argN_value%</span> are keys and values of the <a href="#request_parameters">request parameters</a> array, wrapped with <a href="http://php.net/manual/function.http-build-query.php" target="_blank" class="variable">http_build_query</a> function, because they are must be sent as array only.
                                </p>
                                <p>
                                    The API returns the <a href="#reply">result</a> in a <a href="#formats">format</a>, <a href="#request_parameters">specified in a request</a>, by default it is <span class="variable">json</span>.
                                </p>
                            </div>
                        </section>
                        <section id="authorization_data">
                            <header>Authorization data</header>
                            <div>
                                <p>The authorization data is sent in headers. It is required for every request. At very first request this header should be like this:</p>
                                <p>
                                <code>Authorization: Basic <span class="variable">%email%</span>:<span class="variable">%password%</span></code>
                                </p>
                                <p>Where <span class="variable">%email%</span>:<span class="variable">%password%</span> is base64 encoded line with email and password of an API user, concatenated with a colon.</p>
                                <p>After this request the token will be returned. In all next requests send it in headers like this:</p>
                                <p>
                                <code>Authorization: Bearer <span class="variable">%token%</span></code>
                                </p>
                                <p>The HTTP authorization must be switched on at a server of a website where the API is installed. If not, it can be switched on by adding the next string into <span class="variable">.htaccess</span> file at the website</p>
                                <code>RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]</code>
                            </div>
                        </section>
                        <section id="request_parameters">
                            <header>Request parameters</header>
                            <div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Default value</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>section</td>
                                            <td class="datatype">string</td>
                                            <td>—</td>
                                            <td>The API section</td>
                                        </tr>
                                        <tr>
                                            <td>task</td>
                                            <td class="datatype">string</td>
                                            <td>—</td>
                                            <td>The action, what need to do</td>
                                        </tr>
                                        <tr>
                                            <td>format</td>
                                            <td class="datatype">string</td>
                                            <td>json</td>
                                            <td>The format of reply</td>
                                        </tr>
                                        <tr>
                                            <td>args</td>
                                            <td class="datatype">array</td>
                                            <td>—</td>
                                            <td>Arguments, needed for specified action</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                        <section id="connection">
                            <header>Connection</header>
                            <div>
                                <p>To connect to the API send <span class="variable">open</span> task to <a href="#section_connection" class="variable">connection</a> section like this:</p>
                                <code>$curl = curl_init('<span class="variable">%site_url%</span>/index.php?option=com_jshopping&controller=addon_api');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    '<span class="variable">%authorization_header%</span>'
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'section' => 'connection',
    'task'    => 'open'
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($curl);
curl_close($curl);
if ($res !== false) {
    $res = json_decode($res, true);
}
$token = $res['result'];</code>
                                <p>After this request the token will be returned as a result. Use it for all next requests.</p>
                                <code>Authorization: Bearer <span class="variable">%token%</span></code>
                                <p>Note that the token has the time limit, <span class="variable">60</span> minutes by default. The token's timestamp is updated after every new request, but if there are no activity during this time, the token will expire. In this case need to get a new token again to continue to use the API. The time limit can be changed by the site administrator.</p>
                            </div>
                        </section>
                        <section id="disconnection">
                            <header>Disconnection</header>
                            <div>
                                <p>Always close the connection after finish of work with the API. Call <span class="variable">close</span> task of <a href="#section_connection" class="variable">connection</a> section to do this.</p>
                            </div>
                        </section>
                        <section id="example">
                            <header>Example</header>
                            <div>
                                Initializing access data:
                                <code><span class="variable">$site_url</span> = 'https://example.com';
<span class="variable">$email</span>    = 'example@email.com';
<span class="variable">$password</span> = 'example_password';</code>
                                Opening a connection and getting the token:
                                <code>$curl = curl_init(<span class="variable">$site_url</span> . '/index.php?option=com_jshopping&controller=addon_api');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode(<span class="variable">$email</span> . ':' . <span class="variable">$password</span>)
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'section' => 'connection',
    'task'    => 'open'
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($curl);
curl_close($curl);
if ($res !== false) {
    $res = json_decode($res, true);
}
<span class="variable">$token</span> = $res['result'];</code>
                                Getting information about the connection:
                                <code>$curl = curl_init(<span class="variable">$site_url</span> . '/index.php?option=com_jshopping&controller=addon_api');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . <span class="variable">$token</span>
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'section' => 'connection',
    'task'    => 'info'
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($curl);
curl_close($curl);
if ($res !== false) {
    $res = json_decode($res, true);
}
<span class="variable">$info</span> = $res['result'];</code>
                                Closing the connection:
                                <code>$curl = curl_init(<span class="variable">$site_url</span> . '/index.php?option=com_jshopping&controller=addon_api');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . <span class="variable">$token</span>
]);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
    'section' => 'connection',
    'task'    => 'close'
]));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_exec($curl);
curl_close($curl);</code>
                            </div>
                        </section>
                    </div>
                </section>
                <section id="sections_and_tasks">
                    <header>Sections and tasks</header>
                    <div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>
                                        <table class="args">
                                            <thead>
                                                <tr>
                                                    <th colspan="4">Arguments</th>
                                                </tr>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Default value</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </th>
                                    <th>Result type</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php echo $sections_and_tasks; ?>
                    </div>
                </section>
                <section id="reply">
                    <header>Reply</header>
                    <div>
                        <section>
                            <p>The API returns the array with <span class="variable">status</span>, <span class="variable">code</span>, <span class="variable">report</span> and <span class="variable">result</span> of a request. This array is returned in a <a href="#formats">format</a>, <a href="#request_parameters">specified in a request</a>, by default it is <span class="variable">json</span>. Here is an example of a reply:</p>
                            <code>array (size=4)
    'status' => string 'ok' (length=2)
    'code'   => int 1
    'report' => string 'No errors. Success' (length=18)
    'result' => string 'KfAl9WMorrEjKtCPS7M1FHo1szhOlxS4' (length=32)</code>
                            <p>The <span class="variable">status</span> is the title of a reply. Each status has it's own set of codes and reports. <a href="#reports">Here</a> is the list of possible reports.</p>
                            <p>The <span class="variable">code</span> is for identify a reply programmatically.</p>
                            <p>The <span class="variable">report</span> describes a reply more detailed.</p>
                            <p>The <span class="variable">result</span> is directly the result of a reply.</p>
                            <p>A reply is successful only when it's status is <span class="variable">ok</span>, otherwise — it has an error.</p>
                        </section>
                        <section id="formats">
                            <header>Formats</header>
                            <div>
                                <p>The API returns the result in a format, <a href="#request_parameters">specified in a request</a>, by default it is <span class="variable">json</span>. Here is the list of available formats:</p>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>json</td>
                                            <td>Reply will be JSON-encoded</td>
                                        </tr>
                                        <tr>
                                            <td>var_dump</td>
                                            <td>
                                                Reply will be shown as result of <a href="http://php.net/manual/function.var-dump.php" target="_blank" class="variable">var_dump</a> PHP function. Useful while testing
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                        <section id="reports">
                            <header>Reports</header>
                            <div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Report</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $reports; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
		</main>
        <footer class="column">
            Webdesign
            und
            Programmierung
            MAXXmarketing GmbH
        </footer>
    </body>
</html>