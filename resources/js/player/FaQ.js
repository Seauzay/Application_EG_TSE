

/* retire de la page tout les éléments d'une classe
Attention: si les éléments en question sont liés à des classe, 
cette relation n'est pas mise à jour et l'objet existera toujours
du point de vue de la classe.*/
function removeElementsByClass(className){
    var elements = document.getElementsByClassName(className);
    while(elements.length > 0){
        elements[0].parentNode.removeChild(elements[0]);
    }
}


const QRFactory = (function () {
    return {
        construct: function (root, id, url) {
            // fills the node
            const template = $('#QR-template');
            if (!template.exists())
                throw Error('player-riddle-template does not exist');
            template.clone().appendTo(root);
			//?
            const QRRoot = root.find('.player-riddle-card').last();
            playerRiddleRoot.attr('id', id);
            return playerRiddleRoot;
        }
    };
})();

//classe gérant une question/reponse.
class QR {
    constructor(root, id) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }

        // saves id
        this.id = id;

       
        // constructs
        this.root = QRFactory.construct(root, id);

        // titre button
        this.root.find('.Question').click(() => {
            
            this.toogleReponse();
        });
		
		this.root.find('.Reponse').style.display = 'none';
	}
     
    setQuestion(str) {
        this.root.find('.Question').text(str);
    }

    setReponse(str) {
        this.root.find('.Reponse').text(str);
    }

	toogleReponse(){
		let x = this.root.find('.Reponse');
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
	}
    

    setID(id) {
        this.id = id;
        this.root.find('.player-riddle-card').last().attr('id', id);
    }


}
// classe gérant la grille de questions  
class QRGrid {
    constructor(root) {
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }
        this.root = root;
        this.id = root.prop('id');

        this.QRlist = [];
		const container = $('<ol>', {id: 'Row'});
        this.root.append(container);
       
	}
	
	//ajoute une QR dans la grille
	//Provoque l'affichage sur la page
    addQR(rowNumber, id) {
        const row = this.root.find('.Row').first();
        const QRNumber = row.children().length + 1;
        const QRi = new QR(row, id);
        this.QRlist.push(QRi);
        return QRi;
    }

	//mis a jour de la grille
	//gere l'affichage des enigmes et de leurs contenus.
    remplissageQRgrid() {
        
		//suppression de l'affichage des enigmes dans la page
		removeElementsByClass("card player-riddle-card my-2");
		//suppression des enigmes de la classe
		this.playerRiddles.length = 0;
		
		// Texte des questions réponses pour le remplissage 
		Qlist = ["vos points", "Decompte du temps", "",""];
		Rlist = [ "Au fur et à mesure de votre progression, vous gagnez des points en fonction du temps \
		passé à résoudre les énigmes selon le barème suivant : \n\n\
		moins de 8 minutes : 20 points \n\
		entre 8 et 10 minutes : 10 points \n\
		plus de 10 minutes : -10 points \n\n\
		L'icone à côté de votre nombre de points vous indique votre classement\
		par rapport aux autres équipes",
		"Decompte du temps"," vide 1","vide 2"];
		
		
        for (let i = 0; i<Qlist.length;i++){
            let QR = this.QRlist.find((e) => {
                return e.id === QR.id;
            });
			QR = this.addQR(1);
			QR.setQuestion(Qlist[i]);
			QR.setReponse(Rlist[i]);
				
		}
    };

    
}

exports.QR = QR;
exports.QRGrid = QRGrid;