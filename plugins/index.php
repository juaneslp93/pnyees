<!DOCTYPE html>
<html>
<head>
	<title>Leer Archivo Excel usando PHP</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
	<h2>Ejemplo: Leer Archivos Excel con PHP</h2>	
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Resultados de archivo de Excel.</h3>
      </div>
      <div class="panel-body">
        <div class="col-lg-12">
            
<?php
require_once 'PHPExcel/Classes/PHPExcel.php';
$archivo = "deps.xls";
$inputFileType = PHPExcel_IOFactory::identify($archivo);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($archivo);
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();?>

<table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>codigo</th>
          <th>nombre dep</th>
          <th>codigo muni</th>
          <th>Nombre muni</th>
        </tr>
      </thead>
      <tbody>


<?php
$num=0;
$codigoDep = array();
for ($row = 7; $row <= $highestRow-19; $row++){ $num++;
  if (!in_array($sheet->getCell("A".$row)->getValue(), $codigoDep)) {
    array_push($codigoDep, $sheet->getCell("A".$row)->getValue());
    echo $sheet->getCell("A".$row)->getValue();
  }
?>
       <tr>
          <th scope='row'><?php echo $num;?></th>
          <td><?php echo $sheet->getCell("A".$row)->getValue();?></td>
          <td><?php echo $sheet->getCell("B".$row)->getValue();?></td>
          <td><?php echo $sheet->getCell("C".$row)->getValue();?></td>
          <td><?php echo $sheet->getCell("D".$row)->getValue();?></td>
        </tr>
    	
	<?php	
}
?>
          </tbody>
    </table>
  </div>	
 </div>	
</div>
</body>
</html>
