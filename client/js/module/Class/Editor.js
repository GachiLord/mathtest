import $ from 'jquery'
import task from '../pastetask'
import getVal from '../getVal'
import post from '../post';
import checkThem from '../checkboxesChecker'
import baseurl from '../baseurl';
import massage from '../massage';

 


export default class Editor {
    constructor(){
        this.text = [];
        this.toggle = true;
        this.updateId = null;
    }

    paste(what){
        let block = task(what);
        
        document.execCommand('insertHTML', false, block);
        this.serialize();              
        checkThem($('.editor'));
    }

    addTask(text = ""){
        $('.editor').append(`<div class='card m-auto p-3 mt-3' style='min-height: 10rem;'>
                                <div class='card-title float-end'><button type='button' class='btn-close position-absolute top-0 start-100 translate-middle delete-task' aria-label='Close'></button></div>
                                <div class='card-body task' contenteditable='true' style='outline:none'>${text}</div>
                            </div>`);
    }

    deleteTask(task){
        $(task).detach();
    }

    async create(test){
        //some ajax
        $('.question').each( function(){ getVal($(this), ''); } );
        let publicid = await post('Redactor', 'create', JSON.stringify( test ) );
        if ( await post( 'Info', 'AuthState' ) === 'authed' ) location.replace( 'edit/' + publicid );
        else location.replace( 'launch/' + publicid );
    }

    serialize(){
        let id = 0;
        let pageid = 0;
        $('.task').each( function(){
            $(this).find('.question').each( function(){
                $(this).attr({'answerid':id,'pageid':pageid,'name':pageid});
                 id++; 
            } );
            id = 0;
            pageid++;
        } );
    }

    getAnswers(){
        let answers = [ [] ];
        let id = 0;

        $('.task').each( function(){
            $(this).find('.question').each( function(){
                answers[id].push( getVal( $(this) ) );
            } );
            id++;
            answers.push([]);
        } );


        answers.pop();
        return answers;
    }

    getText(){
        let text = [];

        $('.task').each( function(){
            text.push($(this).html());
        } );


        return text;
        
    }

    async edit(){
        //get data
        let test;
        try{
            test = JSON.parse( await post( 'Redactor', 'get', JSON.stringify( { publicid: document.location.pathname.split("/").pop() } ) ) );
        }
        catch{
            location.assign(baseurl + '/create');
        }
        test.answers = JSON.parse(test.answers);
        test.text = JSON.parse(test.text);

        
        //set properties at editor:
        //toggle
        this.toggle = test.visibility;
        if (this.toggle == 0) $('#vision').trigger('click');
        
        //name
        $('#name').val(test.name);
        $('#time').val(test.time);
        $('#description').val(test.description);

        //id
        this.updateId = test.publicid;
        //text
        test.text.forEach( (item,index) => {
            this.addTask(item);
            $('.delete-task').css({'background-image':`url(${close})`});
            $('.delete-task').on('click', function(){
                $(this).parent().parent().detach();
            });
        });
        //add open button
        $('.finale-options').append(`<input type="button" style="display: inline;padding: 0 2px 0 2px;margin-top: 20px;background: orange;border: orange;" value="Удалить запись о прохождении теста пользователями" class="edit button clear-timers openBut"><a href="${baseurl}/launch/${test.publicid}"><input type="button"
        style="display: inline; padding: 0 2px 0 2px; margin-top: 20px; background:#3048ef; border: 1px solid #3048ef;" value="Открыть" class="button openBut"></a>`);
        //answers
        this.pasteAnswers(test.answers);
        //clear timers
        $('.clear-timers').on('click', async () => {
            massage( await post('Redactor', 'ClearTimers', JSON.stringify( {publicid: this.updateId} ) ) ); 
        });

    }
    async update(){
        let answers = this.getAnswers();
        $('.question').each( function(){ getVal($(this), ''); } );

        massage( await post( 'Redactor', 'edit', JSON.stringify( { name: $('#name').val(), show: this.toggle, text: this.getText(), answers: answers, publicid: this.updateId, time: $('#time').val(), description: $('#description').val() } ) ) );
        console.log(this.getText());

        this.pasteAnswers(answers);
        $('.question').each( function(){
            if ( $(this).attr('check') === 'true' ) $(this).trigger('click');
        } );
    }

    pasteAnswers(answers){
        $('.task').each( function(){
            $(this).attr('Contenteditable',"true")
            $(this).find('.question').each( function(){
                getVal( $(this), answers[ $(this).attr('pageid') ][ $(this).attr('answerid') ] );
                if ( $(this).attr('check') === 'true' ) $(this).trigger('click');
            } );

        } );
    }
}

