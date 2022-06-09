<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                height: 50px;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            .flyleaf {
                position: relative;
                page-break-after: always;
            }


    </style>
</head>
<body>
    <div class="flyleaf">
        <div class="header" style="position: fixed; top:0px; width: 100%; height: 50px; left: 0px; background-color: red;">
            this is header
        </div>
        <div style="display: block; width: 100%; height: 500px;">
            this is content
        </div>
        <div class="footer">this is footer</div>
    </div>
    <header>hello</header>
    <footer>test</footer>
    <main>
        page1
        <div style="page-break-before:always">&nbsp;</div>
        page2
    </main>
</body>
</html>
