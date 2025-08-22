FROM nginx:1.26.2
ENV TZ Asia/Tokyo
ENV RUN_IN_CONTAINER="True"
ENV LANG en_US.UTF-8
 
ARG ENVIRONMENT
ARG UNAME=t8s
ARG UGROUP=t8s
ARG UID=1000
ARG GID=1000
 
RUN apt-get update
RUN apt-get -y install --no-install-recommends less vim git locales procps lsof && \
    localedef -f UTF-8 -i en_US en_US.UTF-8

RUN adduser --disabled-password --gecos "" $UNAME && \
    usermod  --uid $UID $UNAME && \
    groupmod --gid $GID $UGROUP && \
    usermod -a -G nginx $UNAME && \
    mkdir -p /git/openapi && \
    chown -R $UNAME:$UGROUP /git/openapi && \
    chmod 2775 /git/openapi


RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.org && \
    mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf.org
 
COPY nginx/nginx.conf /etc/nginx/nginx.conf 
COPY nginx/security.conf /etc/nginx/conf.d/security.conf
COPY nginx/vhost.${ENVIRONMENT}.conf /etc/nginx/conf.d/vhost.conf

USER $UNAME
WORKDIR /git/openapi
