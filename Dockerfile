FROM nginx:latest

EXPOSE 80/tcp

# Setup nginx
RUN rm /etc/nginx/conf.d/default.conf
COPY docker/adaxisoft.be.conf /etc/nginx/conf.d/default.conf

# Copy contents of site to container
ADD css /usr/share/nginx/html/css
ADD fonts /usr/share/nginx/html/fonts
ADD img /usr/share/nginx/html/img
COPY index.html /usr/share/nginx/html
