<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chromebook Checkout</title>
    <link rel="stylesheet" href="/stylesheets/compiled/main.css">
    <script type="text/javascript" src="/src/scripts/jquery/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="/src/scripts/navbar.js" defer></script>
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon">
</head>
<body id="main">
    <?php require($_SERVER["DOCUMENT_ROOT"]."/inc/navbar.php"); ?>

    <script type="text/javascript">
    let step = 1;

    let settings = {
        school: null,
        grade: null,
        cb_num: null,
        loaner_num: null,
        student: null,
        brief_issue: null,
    };
    </script>

    <div class="options-list">

        <div class="item" data-step="1">
            <h1 id="option-prompt">Please select a school/building.</h1>

            <div class="option button bg-orange" onclick="checks('FES');">Fulton Elementary School</div>
            <div class="option button bg-orange" onclick="checks('RBMS');">River Bend Middle School</div>
            <div class="option button bg-orange" onclick="checks('FHS');">Fulton High School</div>
        </div>

        <div class="item row wrap" data-step="2">
            <h1 id="option-prompt">Please select a grade level.</h1>
            
            <div class="option fes button bg-orange" onclick="checks(0);">Kindergarten</div>
            <div class="option fes button bg-orange" onclick="checks(1);">1st Grade</div>
            <div class="option fes button bg-orange" onclick="checks(2);">2nd Grade</div>
            <div class="option fes button bg-orange" onclick="checks(3);">3rd Grade</div>
            <div class="option fes button bg-orange" onclick="checks(4);">4th Grade</div>
            <div class="option fes button bg-orange" onclick="checks(5);">5th Grade</div>
            <div class="option rbms button bg-orange" onclick="checks(6);">6th Grade</div>
            <div class="option rbms button bg-orange" onclick="checks(7);">7th Grade</div>
            <div class="option rbms button bg-orange" onclick="checks(8);">8th Grade</div>
            <div class="option fhs button bg-orange" onclick="checks(9);">9th Grade</div>
            <div class="option fhs button bg-orange" onclick="checks(10);">10th Grade</div>
            <div class="option fhs button bg-orange" onclick="checks(11);">11th Grade</div>
            <div class="option fhs button bg-orange" onclick="checks(12);">12th Grade</div>
        </div>

        <div class="item" data-step="3">
            <h1 id="option-prompt">Please enter the number of the broken Chromebook.</h1>
            
            <input class="cb-input" id="cb-num" type="text" minlength="1" maxlength="20" placeholder="e.g. 241HP21"/>
            <div class="button bg-green" onclick="checks($('#cb-num').val());">Next</div>

            <div class="option button bg-blue" onclick="showEx();">Show Examples</div>
            <div class="option button bg-blue" onclick="hideEx();">Hide Examples</div>

            <div class="asset-tag-examples">
                <div class="img-container"><img src="/assets/asset_tag_examples/1.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/2.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/3.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/4.jpg"/></div>
                
                <div class="option button bg-blue" onclick="showEx();">Show Examples</div>
                <div class="option button bg-blue" onclick="hideEx();">Hide Examples</div>
            </div>
        </div>

        <div class="item" data-step="4">
            <h1 id="option-prompt">Please enter the number of the loaner Chromebook you are taking.</h1>
            
            <input class="cb-input" id="loaner-num" type="text" minlength="1" maxlength="20" placeholder="e.g. 167HPT19"/>
            <div class="button bg-green" onclick="checks($('#loaner-num').val());">Next</div>

            <div class="option button bg-blue" onclick="showEx();">Show Examples</div>
            <div class="option button bg-blue" onclick="hideEx();">Hide Examples</div>

            <div class="asset-tag-examples">
                <div class="img-container"><img src="/assets/asset_tag_examples/1.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/2.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/3.jpg"/></div>
                <div class="img-container"><img src="/assets/asset_tag_examples/4.jpg"/></div>

                <div class="option button bg-blue" onclick="showEx();">Show Examples</div>
                <div class="option button bg-blue" onclick="hideEx();">Hide Examples</div>
            </div>
        </div>

        <div class="item" data-step="5">
            <h1 id="option-prompt">Please enter your student ID.</h1>
            
            <div id="sid-search">
                <input class="cb-input" id="student" type="text" minlength="1" maxlength="10" placeholder="e.g. 2032034" autocomplete="off"/>
                <div class="searched-students" id="searched-students"><!-- Searched list appends --></div>
            </div>

            <div class="button bg-green" onclick="checks($('#student').val());">Next</div>
        </div>

        <div class="item" data-step="6">
            <h1 id="option-prompt">Briefly describe the issue.</h1>
            
            <input class="cb-input" id="brief-issue" type="text" minlength="1" maxlength="1200" placeholder="Keyboard stopped working"/>
            <div class="button bg-green" onclick="checks($('#brief-issue').val());">Finish</div>
        </div>
        
        <div class="item" data-step="7">
            <h1 class="text-green" id="option-prompt">Thank you! Please take the loaner and put the broken Chromebook in the broken pile.</h1>
            <h3 class="text-green" id="option-prompt">Please wait, resetting...</h3>
        </div>

        <div class="progressions">
            <div class="button bg-blue" onclick="back();" id="back">< Back</div>
            <div class="button bg-blue" onclick="next();" id="next">Next ></div>
        </div>

    </div>

    <script type="text/javascript" src="/src/scripts/main_form.js"></script>
 
</body>
</html>