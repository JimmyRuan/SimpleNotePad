include .env

## PHP Commands
dev-run: ## Running laravel locally
	@php artisan serve --port=${DEV_PORT}

channelling: ## channelling local service using ngrok
	@ngrok http -subdomain=${NGROK_SUB_DOMAIN} ${DEV_PORT}

