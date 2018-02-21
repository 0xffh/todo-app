#!/bin/sh
JWT_DIRECTORY=var/jwt
if [ ! -d $JWT_DIRECTORY ]; then
    mkdir var/jwt
    openssl genrsa -aes256 -passout pass:$1 -out var/jwt/private.pem 4096
    openssl rsa -in var/jwt/private.pem -passin pass:$1 -pubout -out var/jwt/public.pem
fi