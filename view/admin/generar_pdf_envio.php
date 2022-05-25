<?php
// header("Content-Type: application/pdf;charset=utf-8");
require "../../model/conexion.php";
require '../../model/mdl_compra.php';
include "../../plugins/fpdf183/fpdf.php";

class PDF extends FPDF
{
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';
    function WriteHTML($html)
    {
        // Intðrprete de HTML
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Etiqueta
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extraer atributos
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Etiqueta de apertura
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Etiqueta de cierre
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modificar estilo y escoger la fuente correspondiente
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Escribir un hiper-enlace
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
    //limitar cadena
    function limitar_cadena($cadena, $limite, $sufijo){
        // Si la longitud es mayor que el lðmite...
        if(strlen($cadena) > $limite){
            // Entonces corta la cadena y ponle el sufijo
            return substr($cadena, 0, $limite) . $sufijo;
        }
        
        // Si no, entonces devuelve la cadena normal
        return $cadena;
    }
    // Una tabla mðs completa
    function ImprovedTable($header, $data, $nroCompra, $pagina)
    {
        // Anchuras de las columnas
        $w = array(10,40,26,26,18,18,18,30);
        // Cabeceras
        $this->SetY(120);
        $this->SetX(10);
        for($i=0;$i<count($header);$i++){
            $this->SetFillColor(0,0,0);
            $this->SetTextColor(255,255,255);
            $this->Cell($w[$i],8,$header[$i],1,0,'C', true);
        }
            
        $this->Ln();
        $this->SetY(128);
        $this->SetX(10);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        // Datos
        $item = 0;        
        foreach($data as $row)
        {            
            $this->Cell($w[0],7,$row[0],'LR',0,'C');
            $this->Cell($w[1],7,$row[1],'LR',0,'C');
            $this->Cell($w[2],7,$row[2],'LR',0,'C');
            $this->Cell($w[3],7,$row[3],'LR',0,'C');
            $this->Cell($w[4],7,$row[4],'LR',0,'C');
            $this->Cell($w[5],7,$row[5],'LR',0,'C');
            $this->Cell($w[6],7,$row[6],'LR',0,'C');
            $this->Cell($w[7],7,$row[7],'LR',0,'C');
            $this->Ln();
            $item++;
            if($item==15){                
                $pagina++;
                $this->AddPage();
                $this->SetFont('Arial','',12);
                $this->SetY(40);
                $this->SetX(10);
                $this->SetFillColor(0,0,204);
                for($i=0;$i<count($header);$i++){
                    $this->Cell($w[$i],8,$header[$i],1,0,'C');
                }
                $this->SetY(25);
                $this->SetX(10);
                $this->Image(URL_ABSOLUTA.'assets/img/pnyees_logo_1800x1520.png',10,12,30,0,'','#');
                $this->SetFontSize(14);
                $this->SetY(10);
                $this->SetX(143);
                $this->Cell(0,6,"Factura Nro: ".$nroCompra,0,1,'L',true);
                $this->SetFont('Arial','',8);
                $this->SetY(270);
                $this->SetX(173);
                $this->Cell(0,6,"Pag.".$pagina,0,1,'L',true);
                $this->SetFont('Arial','',12);
                $this->SetY(10);
                $this->SetX(143);
                $this->SetY(48);
                $this->SetX(10);
                $item=0;                
            }
        }
        
        // Lðnea de cierre
        $this->Cell(array_sum($w),0,'','T');    
    }

    // Tabla simple
    function BasicTable($data)
    {
        $base = $data[0]["base"];
        $tUnit = $data[0]["total_unit"];
        $descuento = $data[1]["descuento"];
        $tDescuento = $data[1]["total_descuento"];
        $iva = $data[2]["iva"];
        $tIva = $data[2]["total_iva"];
        $compra = $data[3]["compra"];
        $tCompra = $data[3]["total_compra"];

        $this->Ln(4);
        $this->SetX(126);
        $this->SetFillColor(0,0,0);
        $this->SetTextColor(255,255,255);             
        $this->Cell(40,8,$base,1,0,'L',true);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0); 
        $this->Cell(30,8,$tUnit,1,0,'R',false);
        $this->Ln();
        $this->SetX(126);
        $this->SetFillColor(0,0,0);
        $this->SetTextColor(255,255,255);             
        $this->Cell(40,8,$descuento,1,0,'L',true);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0); 
        $this->Cell(30,8,$tDescuento,1,0,'R',false);
        $this->Ln();
        $this->SetX(126);
        $this->SetFillColor(0,0,0);
        $this->SetTextColor(255,255,255);             
        $this->Cell(40,8,$iva,1,0,'L',true);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0); 
        $this->Cell(30,8,$tIva,1,0,'R',false);
        $this->Ln();
        $this->SetX(126);
        $this->SetFillColor(0,0,0);
        $this->SetTextColor(255,255,255);             
        $this->Cell(40,8,$compra,1,0,'L',true);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0); 
        $this->Cell(30,8,$tCompra,1,0,'R',false);
        $this->Ln(15);
    }
}

if (isset($_SESSION["GENERAR_PDF_ENVIO"]) && !empty($_SESSION["GENERAR_PDF_ENVIO"])) {
    $pdf = new PDF();
    foreach ($_SESSION["GENERAR_PDF_ENVIO"] as $value) {
        if ($value["result"]) {        
        
            $item = 0;	
            $totalImpuesto = $totalDescuento = $totalPrecioUni = $total = 0;
            $usuario 				= $value["usuario"];
            $nroCompra 				= $value["nro_compra"];
            $nombreCompleto 		= $value["nombre_completo"];
            $correo 				= $value["correo"];
            $telefono 				= $value["telefono"];
            #datos de facturaciðn
            $datosFacturacion 		= unserialize($value["datos_facturacion"]);
            $nombreDireccionFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["nombre"]:$datosFacturacion["nombre"]));
            $telefonoDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["telefono"]:$datosFacturacion["telefono"]));
            $correoDirFac 			= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["correo"]:$datosFacturacion["correo"]));
            $direccionDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["direccion"]:$datosFacturacion["direccion"]));
            $identificacionDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["identificacion"]:$datosFacturacion["identificacion"]));
            $departamentoDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["departamento"]:$datosFacturacion["departamento"]));
            $municipioDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["municipio"]:$datosFacturacion["municipio"]));
            #datos de envðo
            $datosEnvio 			= unserialize($value["datos_envio"]);
            $nombreDireccionEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["nombre"]:$datosEnvio["nombre"]));
            $telefonoDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["telefono"]:$datosEnvio["telefono"]));
            $correoDirEnv 			= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["correo"]:$datosEnvio["correo"]));
            $direccionDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["direccion"]:$datosEnvio["direccion"]));
            $identificacionDirEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["identificacion"]:$datosEnvio["identificacion"]));
            $departamentoDirEnv 	= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["departamento"]:$datosEnvio["departamento"]));
            $municipioDirEnv 		= ((isset($datosEnvio["datos"])?$datosEnvio["datos"][0]["municipio"]:$datosEnvio["municipio"]));

            $datosP = array();
            for ($i=0; $i <count($value["datos"]) ; $i++) {
                $item++;
                $nombreProducto 		= $pdf->limitar_cadena($value["datos"][$i]["nombre"], 16, "");
                $precioProducto 		= Conexion::formato_decimal($value["datos"][$i]["precio"]);
                $precioBase 			= Conexion::formato_decimal(($value["datos"][$i]["precio"]*$value["datos"][$i]["cantidad"]));
                $impuestoProducto 		= Conexion::formato_decimal($value["datos"][$i]["impuesto"]);
                $descuentoProducto 		= Conexion::formato_decimal($value["datos"][$i]["descuento"]);
                $cantidad 				= $value["datos"][$i]["cantidad"];
                $precioCalculado 		= Conexion::formato_decimal($value["datos"][$i]["precio_calculado"]);					
                $totalImpuesto 			+= $value["datos"][$i]["impuesto"];
                $totalDescuento 		+= $value["datos"][$i]["descuento"];
                $totalPrecioUni 		+= ($value["datos"][$i]["precio"]*$value["datos"][$i]["cantidad"]);
                $total 					+= $value["datos"][$i]["precio_calculado"];

                array_push($datosP, array($item, $nombreProducto, $precioProducto, $precioBase, $impuestoProducto, $descuentoProducto, $cantidad, $precioCalculado));
            }
            # Encabezado tabla detalles
            $header = array('Item', 'Descripciðn', 'Precio U', 'Precio B', 'Iva', 'Desc', 'Cant', 'Subtotal');
            $pdf->AddPage();
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(40);
	        $pdf->SetFillColor(0,0,0);
            $pdf->SetTextColor(255,255,255);
            #Datos del comprador
	        $pdf->Cell(0,6,"Datos de Comprador",0,1,'L',true);
            $pdf->SetY(47);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
	        $pdf->Cell(0,6,"Usuario: ".$usuario,0,1,'L',true);
	        $pdf->Cell(0,6,"Nombre completo: ".$nombreCompleto,0,1,'L',true);
	        $pdf->Cell(0,6,"Telðfono: ".$telefono,0,1,'L',true);
	        $pdf->Cell(0,6,"Correo: ".$correo,0,1,'L',true);
	        $pdf->Ln(1);
            #Datos de facturaciðn
            $pdf->SetY(76);
	        $pdf->SetFillColor(0,0,0);
            $pdf->SetTextColor(255,255,255);
	        $pdf->Cell(0,6,"Datos de facturaciðn",0,1,'L',true);
            $pdf->SetY(83);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0);
	        $pdf->Cell(0,6,"Identificaciðn/Nit: ".$identificacionDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Telðfono: ".$telefonoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Correo: ".$correoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Direcciðn: ".$direccionDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Departamento: ".$departamentoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Municipio: ".$municipioDirFac,0,1,'L',true);
            #datos de Envðo
            $pdf->SetY(76);
            $pdf->SetX(110);
	        $pdf->SetFillColor(0,0,0);  
            $pdf->SetTextColor(255,255,255); 
	        $pdf->Cell(0,6,"Datos de envðo",0,1,'L',true);
            $pdf->SetY(83);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetTextColor(0,0,0); 
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Identificaciðn/Nit: ".$identificacionDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Telðfono: ".$telefonoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Correo: ".$correoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Direcciðn: ".$direccionDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Departamento: ".$departamentoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Municipio: ".$municipioDirFac,0,1,'L',true);
	        $pdf->Ln(4);
            #Logo
            $pdf->Image(URL_ABSOLUTA.'assets/img/pnyees_logo_1800x1520.png',10,12,30,0,'','#');
            $pdf->SetFontSize(14);
            $pdf->SetY(10);
            $pdf->SetX(143);
            $pdf->Cell(0,6,"Factura Nro: ".$nroCompra,0,1,'R',true);
            $pdf->SetFontSize(8);
            // $pdf->SetY(10);
            $pasarela = Conexion::consultaSystem("relacion", "config_facturacion");
            if ($pasarela["estado"]) {
                for ($i=0; $i <count($pasarela["datos"]) ; $i++) { 
                    $id = $pasarela["datos"][$i]["id"];
                    $valor = $pasarela["datos"][$i]["valor"];
                    if($id==1){
                        $tituloEmpresa =  $valor;
                    }else if($id==2){
                        $nitEmpresa =  $valor;
                    }else if($id==3){
                        $contactoEmpresa = $valor;
                    }else if($id==4){
                        $direcionEmpresa = $valor;
                    }else if($id==5){
                        $correoEmpresa = $valor;
                    }
                }
            }
            $pdf->SetX(144);
            $pdf->Cell(0,4,$tituloEmpresa,0,1,'R',true);
            $pdf->SetX(144);
            $pdf->Cell(0,4,"NIT: $nitEmpresa",0,1,'R',true);
            $pdf->SetX(144);
            $pdf->Cell(0,4,$contactoEmpresa,0,1,'R',true);
            $pdf->SetX(144);
            $pdf->Cell(0,4,$correoEmpresa,0,1,'R',true);
            $pdf->SetX(144);
            $pdf->Cell(0,4,$direcionEmpresa,0,1,'R',true);

            $pagina = 1;
            $pdf->SetFont('Arial','',8);
            $pdf->SetY(270);
            $pdf->SetX(173);
            $pdf->Cell(0,6,"Pag.".$pagina,0,1,'L',true);
            $pdf->SetFont('Arial','',12);
            #Creaciðn de tabla detalle 
            
            $pdf->ImprovedTable($header, $datosP, $nroCompra, $pagina);
            $dataBasic = array(
                array(
                    "base"=>"Total precio base", 
                    "total_unit"=>Conexion::formato_decimal($totalPrecioUni)), 
                array(
                    "descuento"=>"Total descuento", 
                    "total_descuento"=>Conexion::formato_decimal($totalDescuento)
                ), 
                array(
                    "iva"=>"Total Iva", 
                    "total_iva"=>Conexion::formato_decimal($totalImpuesto)), 
                array(
                    "compra"=>"Total compra", 
                    "total_compra"=>Conexion::formato_decimal($total)
                )
            );             
            $pdf->BasicTable($dataBasic);            
        }    
    }
    $pdf->Output();
    
}else{
    echo 'Error al cargar el pdf';
}
?>