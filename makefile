## generate: Генерация PHP Laravel API-кода из OpenAPI спецификации.
## Использует openapitools/openapi-generator-cli с кастомными шаблонами.
generate:
	composer openapi:jar && composer openapi:generate

serve:
	docker compose -f .\docker-compose.yml up --build

serve-down:
	docker compose -f docker-compose.serve.yml down
