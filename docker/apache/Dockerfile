ARG APACHE_VERSION=""
FROM httpd:${APACHE_VERSION:+${APACHE_VERSION}}

RUN apt-get update && apt-get install -y

RUN rm -rf /var/lib/apt/lists/*

# Copy apache vhost file to proxy php requests to php-fpm container
COPY nettech.io.apache.conf /usr/local/apache2/conf/nettech.io.apache.conf
# Add custom Apache config file
ADD nettech.io.apache.conf /etc/apache2/sites-available/nettech.io.apache.conf
RUN echo "Include /usr/local/apache2/conf/nettech.io.apache.conf" \
    >> /usr/local/apache2/conf/httpd.conf

WORKDIR /var/www/html/
