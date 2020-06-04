

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
        construct: function (root) {
            // fills the node
            const template = $('#QR-template');
            if (!template.exists())
                throw Error('player-riddle-template does not exist');
            template.clone().appendTo(root);
            const QRRoot = root.find('.QR').last();
            QRRoot.attr('id', 'QR');
            return QRRoot;
        }
    };
})();

//classe gérant une question/reponse.
class QR {
    constructor(root) {
        // assures that root node is quite correct
        if (!(root instanceof jQuery)) {
            if (typeof root !== 'string')
                throw 'Invalid parameter in constructor of TabList.';
            root = $(root);
        }


       
        // constructs
        this.root = QRFactory.construct(root);

        // titre button
        this.root.find('.question').click(() => {
            
            this.toogleReponse();
        });
		
	}
     
    setQuestion(str) {
        this.root.find('.question').text(str);
    }

    setReponse(str) {
        this.root.find('.reponse').text(str);
    }

	toogleReponse(){
		let x = this.root.find('.reponse');
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
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
    addQR() {
        const row = this.root.find('.Row').first();
        const QRNumber = row.children().length + 1;
        const QR = new QR(row);
        this.QRlist.push(QR);
        return QR;
    }

	//mis a jour de la grille
	//gere l'affichage des enigmes et de leurs contenus.
    remplissageQRgrid() {
        
		//suppression de l'affichage des enigmes dans la page
		removeElementsByClass("QR");
		//suppression des enigmes de la classe
		this.QRlist.length = 0;
		
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
			
			QR = this.addQR();
			QR.setQuestion(Qlist[i]);
			QR.setReponse(Rlist[i]);
				
		}
    }

    
}

exports.QR = QR;
exports.QRGrid = QRGrid;