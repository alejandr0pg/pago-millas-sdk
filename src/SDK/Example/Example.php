<?php

namespace PlaceToPay\SDK\Example;

use PlaceToPay\SDK\Client\PlaceToPayClientBuilder;
use PlaceToPay\SDK\Exception\PlaceToPayException;

class ExampleClass
{
    protected function __construct()
    {
        // Implementamos la sdk
        try {

            $placeToPayClient = PlaceToPayClientBuilder::builder()
                ->withApiUrl("{YOUR API URL}")
                ->withClientId("{YOUR CLIENT ID}")
                ->withClientSecret("{YOUR CLIENT SECRET}")
                ->withRedirectUrl("{YOUR CALLBACK URL}")
                ->build();

            /*  
            Get Points
            @params
            $merchantId: number ( numero del cliente )

            @description
            -Endpoint para obtener la cantidad de millas disponibles
            */
            $response = $placeToPayClient->getPoints($merchantId);
            /* RESPONSE:
                {
                    "Data": {
                    "miles": 0,
                    "index_conversion": 0
                    },		
                    "Message": "string"
                }
            */

            /*  
            Lock Points
            @params
            $milles: number

            @description
            -Endpoint para bloquear millas del cliente
            */
            $response = $placeToPayClient->lockPoints($milles);
            /* RESPONSE:
                {
                    "Data": {
                        "document_id": 0
                        "miles": 0,
                    },
                    "Message": "string"
                }
            */

            /*  
            Debit Points
            @params
            $documentId: number

            @description
            -Endpoint para debitar la cantidad de millas disponibles según el documentId
            */
            $response = $placeToPayClient->debitPoints($documentId);
            /* RESPONSE:
                {
                    "Data": {
                        "document_id": 0
                    },
                    "Message": "string"
                }
            */

            /*  
            Cancel Transaction
            @params
            $documentId: number

            @description
            -Endpoint para cancelar la transacción
            */
            $response = $placeToPayClient->cancelTransaction($documentId);
            /* RESPONSE:
                {
                    "Data": {
                        "document_id": 0
                    },
                    "Message": "string"
                }
            */

            /*  
            Reverse Point
            @params
            $documentId: number

            @description
            -Endpoint para devolver las millas debitadas.
            */
            $response = $placeToPayClient->reversePoint($documentId);
            /* RESPONSE:
                {
                    "Data": {
                        "document_id": 0
                    },
                    "Message": "string"
                }
            */


        } catch (PlaceToPayException $sdkException) {
            print $sdkException->getMessage();
        }
    }
}
