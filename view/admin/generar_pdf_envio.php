<?php
header("Content-Type: application/pdf;charset=utf-8");
session_start();
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
        // IntÈrprete de HTML
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
    // Una tabla mùs completa
    function ImprovedTable($header, $data, $nroCompra, $pagina)
    {
        // Anchuras de las columnas
        $w = array(10,40,26,26,18,18,18,30);
        // Cabeceras
        $this->SetY(120);
        $this->SetX(10);
        $this->SetFillColor(204,204,204);
        for($i=0;$i<count($header);$i++){
            $this->Cell($w[$i],8,$header[$i],1,0,'C');
        }
            
        $this->Ln();
        $this->SetY(128);
        $this->SetX(10);
        $this->SetFillColor(255,255,255);
        // Datos
        $item = 0;        
        foreach($data as $row)
        {            
            $this->Cell($w[0],7,$row[0],'LR',0,'C');
            $this->Cell($w[1],7,$row[1],'LR');
            $this->Cell($w[2],7,$row[2],'LR');
            $this->Cell($w[3],7,$row[3],'LR');
            $this->Cell($w[4],7,$row[4],'LR');
            $this->Cell($w[5],7,$row[5],'LR');
            $this->Cell($w[6],7,$row[6],'LR');
            $this->Cell($w[7],7,$row[7],'LR');
            $this->Ln();
            $item++;
            if($item==15){                
                $pagina++;
                $this->AddPage();
                $this->SetFont('Arial','',12);
                $this->SetY(40);
                $this->SetX(10);
                // $this->SetFillColor(204,204,204);
                for($i=0;$i<count($header);$i++){
                    $this->Cell($w[$i],8,$header[$i],1,0,'C');
                }
                $this->SetY(25);
                $this->SetX(10);
                $this->Image('../../assets/img/pnyees_logo_1800x1520.png',10,12,30,0,'','#');
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
        
        // Lùnea de cierre
        $this->Cell(array_sum($w),0,'','T');    
    }

    // Tabla simple
    function BasicTable($header, $data)
    {
        
        // Cabecera
        foreach($header as $col)
            // $this->SetX(150);
            $this->Cell(40,7,$col,1);
        $this->Ln(4);
        
        // Datos
        // $this->SetX(150);
        foreach($data as $row)
        {$this->SetX(116);
            foreach($row as $col)
                
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }
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
            #datos de facturaciùn
            $datosFacturacion 		= unserialize($value["datos_facturacion"]);
            $nombreDireccionFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["nombre"]:$datosFacturacion["nombre"]));
            $telefonoDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["telefono"]:$datosFacturacion["telefono"]));
            $correoDirFac 			= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["correo"]:$datosFacturacion["correo"]));
            $direccionDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["direccion"]:$datosFacturacion["direccion"]));
            $identificacionDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["identificacion"]:$datosFacturacion["identificacion"]));
            $departamentoDirFac 	= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["departamento"]:$datosFacturacion["departamento"]));
            $municipioDirFac 		= ((isset($datosFacturacion["datos"])?$datosFacturacion["datos"][0]["municipio"]:$datosFacturacion["municipio"]));
            #datos de envùo
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
                $nombreProducto 		= $value["datos"][$i]["nombre"];
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
            # Encabezado tabla detalle
            $header = array('Item', 'Descripciùn', 'Precio U', 'Precio B', 'Iva', 'Desc', 'Cant', 'Subtotal');
            $pdf->AddPage();
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(40);
	        $pdf->SetFillColor(204,204,204);
            #Datos del comprador
	        $pdf->Cell(0,6,"Datos de Comprador",0,1,'L',true);
            $pdf->SetY(47);
            $pdf->SetFillColor(255,255,255);
	        $pdf->Cell(0,6,"Usuario: ".$usuario,0,1,'L',true);
	        $pdf->Cell(0,6,"Nombre completo: ".$nombreCompleto,0,1,'L',true);
	        $pdf->Cell(0,6,"Telùfono: ".$telefono,0,1,'L',true);
	        $pdf->Cell(0,6,"Correo: ".$correo,0,1,'L',true);
	        $pdf->Ln(1);
            #Datos de facturaciùn
            $pdf->SetY(76);
	        $pdf->SetFillColor(204,204,204);
	        $pdf->Cell(0,6,"Datos de facturaciùn",0,1,'L',true);
            $pdf->SetY(83);
            $pdf->SetFillColor(255,255,255);
	        $pdf->Cell(0,6,"Identificaciùn/Nit: ".$identificacionDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Telùfono: ".$telefonoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Correo: ".$correoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Direcciùn: ".$direccionDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Departamento: ".$departamentoDirFac,0,1,'L',true);
	        $pdf->Cell(0,6,"Municipio: ".$municipioDirFac,0,1,'L',true);
            #datos de Envùo
            $pdf->SetY(76);
            $pdf->SetX(110);
	        $pdf->SetFillColor(204,204,204);   
	        $pdf->Cell(0,6,"Datos de envùo",0,1,'L',true);
            $pdf->SetY(83);
            $pdf->SetFillColor(255,255,255);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Identificaciùn/Nit: ".$identificacionDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Telùfono: ".$telefonoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Correo: ".$correoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Direcciùn: ".$direccionDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Departamento: ".$departamentoDirFac,0,1,'L',true);
            $pdf->SetX(110);
	        $pdf->Cell(0,6,"Municipio: ".$municipioDirFac,0,1,'L',true);
	        $pdf->Ln(4);
            #Logo
            $pdf->Image('../../assets/img/pnyees_logo_1800x1520.png',10,12,30,0,'','#');
            $pdf->SetFontSize(14);
            $pdf->SetY(10);
            $pdf->SetX(143);
            $pdf->Cell(0,6,"Factura Nro: ".$nroCompra,0,1,'L',true);
            $pdf->SetFontSize(8);
            // $pdf->SetY(10);
            $pdf->SetX(45);
            $pdf->Cell(0,6,"Piedras Naturales y enchapes el sur ",0,1,'L',true);
            $pdf->SetX(45);
            $pdf->Cell(0,6,"Nit : 0-123456789 ",0,1,'L',true);
            $pdf->SetX(45);
            $pdf->Cell(0,6,"Contacto : +57 3003334785 ",0,1,'L',true);
            $pdf->SetX(45);
            $pdf->Cell(0,6,"Direcciùn : Km 60, Caldas, Antioquia, Colombia ",0,1,'L',true);

            $pagina = 1;
            $pdf->SetFont('Arial','',8);
            $pdf->SetY(270);
            $pdf->SetX(173);
            $pdf->Cell(0,6,"Pag.".$pagina,0,1,'L',true);
            $pdf->SetFont('Arial','',12);
            #Creaciùn de tabla detalle 
            
            $pdf->ImprovedTable($header, $datosP, $nroCompra, $pagina);
            $dataBasic = array(array("Total precio base", Conexion::formato_decimal($totalPrecioUni)), array("Total descuento", Conexion::formato_decimal($totalDescuento)), array("Total Iva", Conexion::formato_decimal($totalImpuesto)), array("Total compra", Conexion::formato_decimal($total)));
            $pdf->BasicTable(array(), $dataBasic);
            
        }    
    }
    $pdf->Output();
    
}else{
    echo 'Error al cargar el pdf';
}
?>