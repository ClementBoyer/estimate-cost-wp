(function() 
{
    tinymce.PluginManager.add('submit_button', function(editor, url) {
         // ajoute un bouton à tinyMCE
        editor.addButton('submit_button', 
        {
            text:'Envoyer',
            icon:false,
        onclick: function() 
        {     
            // On insère le contenu à l'endroit du curseur
            editor.insertContent
            (
                '<div class="text-center"'
                +'<button type="submit" class="btn btn-primary mt-4" > Envoyer </button>' 
                +'</div>'//.text-center               
            );
        }
        });
});
})();



