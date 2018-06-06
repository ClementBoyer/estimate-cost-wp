(function() {
    tinymce.PluginManager.add('produit_button', function(editor, url) {
      // ajoute un bouton à tinyMCE
      editor.addButton('produit_button', {
  
      // Titre boutton
      text: 'Produit',
      icon: false,
      onclick: function() {
        // Ouverture fenêtre 
        editor.windowManager.open( {
          // titre du popup
          title: 'Entrez votre produit',
          body: [
          // champ label
          {
            type: 'textbox',
            name: 'nom',
            label: 'Nom produit'
          },
          {
            type: 'textbox',
            name: 'img',
            label: 'Lien image',
            value: '',
            classes: 'my_input_image',
          },
          {
            type: 'button',
            name: 'Image',
            label: '',
            text: 'Téléchargement image',
            classes: 'my_upload_button',
          },
          {
            type: 'textbox',
            name: 'description',
            label: 'Description produit',
            multiline: true,
            minWidth: 350,
            minHeight: 150
          },
          {
            type   : 'combobox',
            name   : 'combobox',
            label  : 'Type',
            values : [
                { text: 'Test', value: 'test' },
                { text: 'Test2', value: 'test2' }
            ]
          },
          ],
          
          onsubmit: function(e) 
          {

            // On insère le contenu à l'endroit du curseur
            editor.insertContent(
            '<h2 class="text-center">'+ e.data.nom +'</h2>'
            +'<div class="thumbnail">'
            +'<img src="'+ e.data.img +'" alt="'+ e.data.nom + e.data.combobox +'" style="width:100%">'
            +'<div class="caption">'
            +'<h5 class="text-center mt-3">'+e.data.combobox + '</h5>'
            +'<p>'+ e.data.description +'</p>'
            +'<label> Quantité : </label>'
            +'<input type="number" name="nbr" min=0>'
            +'</div>'//.caption
            +'</div>'//.thumbnail
            );

            
            }
          });
        }
      });
    });
  })();

  jQuery(document).ready(function($){
    $(document).on('click', '.mce-my_upload_button', upload_image_tinymce);

    function upload_image_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-my_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Ajouter Image',
            button: {
                text: 'Ajouter Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.url);
        });
        custom_uploader.open();
    }
});


