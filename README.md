# Placetopay Pago con millas SDK
SDK para la integración de pagos con millas ( PPM ) a Placetopay.
Con este código, podrá conectarse rápidamente con el servicio de redireccionamiento PlacetoPay.


## Requerimientos

* PHP 7.3.15
* Composer

## Instalación

Utilizando composer para tu proyecto

```
composer require placetopay/pago-millas-sdk
```

O bien, si solo desea ejecutar los ejemplos contenidos en este proyecto, 
ejecute "composer install" para instalar las dependencias

## Modo de uso

Crear una instancia de la sdk a través del build constructor de la sdk

```
use PlacetoPay\Client\ClientBuilder;
use PlacetoPay\Client\PlaceToPayClientBuilder;

//alguna implementacion de psr5/cache
$cachePSR5 = new CacheImplementacion();

$placeToPayClient = PlaceToPayClientBuilder::builder()
    ->withApiUrl('apiURL')
    ->withClientId('client_id')
    ->withClientSecret('client_secret')
    ->withRedirectUrl('redirectUrl')
    ->withCache($cachePSR5)
    ->build();
```

### Solicitar puntos disponibles y tasa de conversión

Simplemente proporcione el identificador del cliente y si es exitoso 
le devolverá las millas disponibles y la tasa de conversión actual

```
$getPointsResponse = $placeToPayClient->getPoints("mymerchantid");

if ($getPointsResponse->isSuccessful()) {
    print("Tengo la cantidad de {$getPointsResponse->getMiles()}")
    print("a una taza de conversión de {$getPointsResponse->getIndexOfConversion()}");
} else {
    print(" Oh Oh ocurrio un error {$getPointsResponse->getMessage()}");
}
```

### Bloquear puntos al cliente para luego debitar

```
$response = $placeToPayClient->lockPoints(1000);

if ($response->isSuccessful()) {
    print("los puntos fueron bloqueados creada transaccion con id {$response->getDocumentId()}");
} else {
    print(" Oh Oh ocurrio un error {$response->getMessage()}");
}
```

### Debitar puntos

```
$response = $placeToPayClient->debitPoints($document_id);

if ($response->isSuccessful()) {
    print("los puntos fueron debitados");
} else {
    print(" Oh Oh ocurrio un error {$response->getMessage()}");
}
```

### Cancelar transacción

```
$response = $placeToPayClient->cancelTransaction($document_id);

if ($response->isSuccessful()) {
    print("La transacción ha sido cancelada.");
} else {
    print(" Oh Oh ocurrio un error {$response->getMessage()}");
}
```

### Reversar los puntos

```
$response = $placeToPayClient->reversePoint($document_id);

if ($response->isSuccessful()) {
    print("Los puntos han sido regresados.");
} else {
    print(" Oh Oh ocurrio un error {$response->getMessage()}");
}
```
