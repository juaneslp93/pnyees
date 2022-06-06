//Función que lanza el copiado del código
function copiarAlPortapapeles(ev){
	var codigoACopiar = document.getElementById('textoACopiar');    //Elemento a copiar
	//Debe estar seleccionado en la página para que surta efecto, así que...
	var seleccion = document.createRange(); //Creo una nueva selección vacía
	seleccion.selectNodeContents(codigoACopiar);    //incluyo el nodo en la selección
	//Antes de añadir el intervalo de selección a la selección actual, elimino otros que pudieran existir (sino no funciona en Edge)
	window.getSelection().removeAllRanges();
	window.getSelection().addRange(seleccion);  //Y la añado a lo seleccionado actualmente
	try {
		var res = document.execCommand('copy'); //Intento el copiado
		if (res)
			exito();
		else
			fracaso();

		mostrarAlerta();
	}
	catch(ex) {
		excepcion();
	}
	window.getSelection().removeRange(seleccion);
}

//Detectar pegado (se puede hacer para toda la página interceptándolo en el documento o solo en elementos concretos, como es el caso)
// document.getElementById('ParaPegar').addEventListener('paste', interceptarPegado);

function interceptarPegado(ev) {
	alert('Has pegado el texto:' + ev.clipboardData.getData('text/plain'));
}

///////
// Auxiliares para mostrar y ocultar mensajes
///////
var divAlerta = document.getElementById('alerta');

function exito() {
	divAlerta.innerText = '¡¡Clave copiada al portapapeles!!';
	divAlerta.classList.add('alert-success');
	Swal.fire({
		title: 'Clave copiada',
		text: 'Por favor ingrese su nueva clave en los campos solicitados',
		icon: 'success'
	});
}

function fracaso() {
	divAlerta.innerText = '¡¡Ha fallado el copiado al portapapeles!!';
	divAlerta.classList.add('alert-warning');
}

function excepcion() {
	divAlerta.innerText = 'Se ha producido un error al copiar al portapaples';
	divAlerta.classList.add('alert-danger');
}

function mostrarAlerta() {
	divAlerta.classList.remove('invisible');
	divAlerta.classList.add('visible');
	setTimeout(ocultarAlerta, 1500);
}

function ocultarAlerta() {
	divAlerta.innerText = '';
	divAlerta.classList.remove('alert-success', 'alert-warning', 'alert-danger', 'visible');
	divAlerta.classList.add('invisible');
}

editarUsuarios = {
	iniciar: function(){
		this.cargarInfo();
	},
	cargarInfo: function(){
		var self = this;
		$.ajax({
			url: '../controller/ctr_procesos_user.php',
			type: 'POST',
			dataType: 'json',
			data: {'entrada': 'cargarDatosEdit'},
		})
		.done(function(result) {
			$("#edit-perfil").html(result.datos);
			self.cargarFormEdit();
		})
		.fail(function() {
			console.log("error");
		});
	},
	generateRandomString: function(num){
		const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789*_$.#!';
		let result1= ' ';
		const charactersLength = characters.length;
		for ( let i = 0; i < num; i++ ) {
			result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
		}
		return result1;
	},
	cargarFormEdit: function(){
		var self = this;
		this.cargarFormEditClave();
		this.cargarFormEditDatos();
		$("body").on('click', '#btn-al-pass', function(){		
			var newPas = self.generateRandomString(12);
			$("#claveEdit").val(newPas);
			Swal.fire({
				title:'Clave generada!',
				html:`<p>La nueva clave es: <b id="textoACopiar">`+newPas+`</b> por favor ingresela en los campos solicitados, adicional guardela en un lugar donde esté segura y que no la pierda. <br><br>
					<button class="btn btn-warning" id="btnCopyPass" ><i class="fa fa-copy"></i> Copiar la clave</button>
				</p>`,
				icon: 'info'
			});
			document.getElementById('btnCopyPass').addEventListener('click', copiarAlPortapapeles);	
		})
	},
	cargarFormEditClave: function(){
		$('body').on('submit', '#formEditPass', function(e){
			e.preventDefault();
			Swal.fire({
				title: '¿Realmente desea cambiar su clave?',
				text: '¡Importante!. Si realmente desea continuar asegurese de haber guardado la nueva clave donde pueda hacer uso de ella o memorizado.',
				icon: 'info',
				showDenyButton: true,
				showCancelButton: false,
				confirmButtonText: 'Si, deseo continuar',
				denyButtonText: 'No, cancela esta acción',
			}).then((promised)=> {
				if (promised.isConfirmed) {
					$.ajax({
						url: '../controller/ctr_procesos_user.php',
						type: 'POST',
						dataType: 'json',
						data: $(this).serialize(),
					})
					.done(function(result) {
						if (result.continue) {
							Swal.fire({
							  icon: 'success',
							  title: '¡Proceso exitoso!',
							  text: result.mensaje
							})
						}else{
							Swal.fire({
							  icon: 'warning',
							  title: '¡Proceso detenido!',
							  text: result.mensaje
							})
						}
					})
					.fail(function() {
						console.log("error");
					})
					.always(function(){
						document.getElementById("formEditPass").reset();
					});
				}else if(promised.isDenied){
					document.getElementById("formEditPass").reset();
				}
			})
		});
	},
	cargarFormEditDatos: function(){
		$('body').on('submit', '#formEditDatos', function(e){
			e.preventDefault();
			Swal.fire({
				title: '¿Realmente desea cambiar sus datos?',
				text: 'Por favor verifique que estén correctos',
				icon: 'info',
				showDenyButton: true,
				showCancelButton: false,
				confirmButtonText: 'Si, deseo continuar',
				denyButtonText: 'No, cancela esta acción',
			}).then((promised)=> {
				if (promised.isConfirmed) {
					$.ajax({
						url: '../controller/ctr_procesos_user.php',
						type: 'POST',
						dataType: 'json',
						data: $(this).serialize(),
					})
					.done(function(result) {
						if (result.continue) {
							Swal.fire({
							  icon: 'success',
							  title: '¡Proceso exitoso!',
							  text: result.mensaje
							})
						}else{
							Swal.fire({
							  icon: 'warning',
							  title: '¡Proceso detenido!',
							  text: result.mensaje
							})
						}
					})
					.fail(function() {
						console.log("error");
					})
					.always(function(){
						document.getElementById("formEditPass").reset();
					});
				}else if(promised.isDenied){
					document.getElementById("formEditPass").reset();
				}
			})
		});
	}
}
jQuery(document).ready(function($) {	
	editarUsuarios.iniciar();
});