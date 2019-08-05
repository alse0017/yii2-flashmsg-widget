# Попап сообщения

Записать сообщения в сессию

```php
Yii::$app->getSession()->addFlash('error', 'Error Message');
Yii::$app->getSession()->addFlash('success', 'Success Message');
Yii::$app->getSession()->addFlash('info', 'Info Message');
```

Показать сообщения

```php
\alse0017\flashmsg\Widget::widget();
```
