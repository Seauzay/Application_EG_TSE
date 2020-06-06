


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
		
       
	}

	//remplissage de la grille
    remplissageQRgrid() {
        
		
		// Texte des questions réponses pour le remplissage 
		Qlist = ["Que Signifie le décompte à gauche de l'écran ?", "J'ai résolu une énigme, comment passer à l'étape suivante ?", "Que faire en cas de problème ?"];
		Rlist = [ "Au fur et à mesure de votre progression, vous gagnez des points en fonction du temps \
		passé à résoudre les énigmes selon le barème suivant : \n\n\
		moins de 8 minutes : 20 points \n\
		entre 8 et 10 minutes : 10 points \n\
		plus de 10 minutes : -10 points \n\n\
		L'icone à côté de votre nombre de points vous indique votre classement\
		par rapport aux autres équipes",
		"Vous avez terminé une étape ? Bravo ! Il ne vous reste plus qu'a la valider, grâce au game master de l'énigme :\
		demandez lui le code de validation, et entrez le dans le champ correspondant pour passer à l'étape suivante.",
		"Contactez le game master le plus proche. Les game masters sont reconnaissables grâce à leurs badges."];
		
		let faq = document.getElementById('FaQ');
		let conteneur = document.createElement('div');
		faq.appendChild(conteneur);
        for (let i = 0; i<Qlist.length;i++){
			
				
			let QR = document.createElement('div');
			let Q = document.createElement('h4');
			Q.textContent = Qlist[i];
			
			let R = document.createElement('p');
			R.textContent= Rlist[i];
			R.style.display = 'none';
			
			Q.addEventListener('click', () => {
				if (R.style.display === "none") {
					R.style.display = "block";
				} else {
					R.style.display = "none";
				}
			});
			
			QR.appendChild(Q);
			QR.appendChild(R);
			
			conteneur.appendChild(QR);
		}
		
    }

    
}

exports.QRGrid = QRGrid;
