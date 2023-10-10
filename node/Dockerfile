FROM node:19.0.0

RUN apt-get -qq update \
	&& apt-get install apt-utils --assume-yes

RUN apt-get install zip --assume-yes

RUN apt-get install curl --assume-yes

RUN apt-get install ruby-dev --assume-yes \
	&& apt-get install rubygems --assume-yes \
	&& gem update \
	&& gem install compass \
	&& gem install dpl

RUN npm -g install typings --silent

RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.32.1/install.sh | bash

RUN apt-get install yarn --assume-yes

WORKDIR /var/www/app

CMD ["node"]