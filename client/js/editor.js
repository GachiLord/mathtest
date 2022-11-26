import Test from './module/Class/Test'
import Editor from './module/Class/Editor'
import $ from 'jquery'
import escape_text from './module/escapeText'
import checkThem from './module/checkboxesChecker'
import main from './module/main'
import massage from './module/massage'



const editor = new Editor($('.editor')); 




    $( ()=>{
        main();
        

        //make focus on task
        $('.editblock').on('mousedown', ()=> { return false; });
        $('input').on('click', function (){ $(this).trigger('focus'); });
        //paste in caret`s position
        $('body').on('paste', '.editor', function(e){
            e.preventDefault();
            let text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertHtml', false, escape_text(text));
        });
        //paste html ELement
        $('[what]').on('click', function(){
            editor.paste($(this).attr('what'));
        } );
        //toggle
        $('#vision').on('change', function(){
            editor.toggle = !editor.toggle;
        });
        //add task
        $('.add-task').on('click', function(){
            editor.addTask();

            $('.delete-task').on('click', function(){
                let parent = $(this).parent().parent();
                editor.deleteTask(parent);
            });
            
        });

        //delete task
        $('.delete-task').on('click', function(){
            let parent = $(this).parent().parent();
            editor.deleteTask(parent);

        } );
        //create test
        $('#save').on('click', function (e) {
            e.preventDefault();
            
            if ($('#name').val() !== '' && $('#description').val() !== ''){
                if ( !($('#name').val().length > 100) && !($('#description').val().length > 100) ) {
                    //prepare text
                    editor.serialize();
                    checkThem($('.editor'));
                    //create new Test
                    const test = new Test( $('#name').val() );
                    //add content in test
                    test.answers = editor.getAnswers();
                    test.text = editor.getText();
                    test.show = editor.toggle;
                    test.time = $('#time').val();
                    test.description = $('#description').val();
                    //sending test
                    editor.create(test);
                }
                else massage('Название или описание слишком длинное', false);
                
            }
            else massage('Вы не ввели название теста или не заполнили поля ответов', false);
        });
        //edit test
        if ( document.location.href.includes('edit') ){
            editor.edit();
            $('#save').off('click');
            $('#save').on('click', function(e){
                e.preventDefault();

                checkThem($('.editor'));
                if ( $('#name').val() !== '' && $('#description').val() !== '' ){
                    if ( !($('#name').val().length > 100) && !($('#description').val().length > 100) ){
                        editor.serialize();
                        editor.update();  
                    }
                    else massage('Название или описание слишком длинное', false); 
                       
                }
                else massage('Вы не ввели название теста или не заполнили поля ответов', false);                    

            });
        }
    } );