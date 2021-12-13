FROM nginx:1.21

ADD ./docker-config/nginx/vhost.conf /etc/nginx/conf.d/default.conf