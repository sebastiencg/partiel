
```markdown
# Contrôleur Contributions

Ce contrôleur Symfony gère diverses actions liées aux contributions pour les événements.

## Points d'accès

### Créer une suggestion

Créez une suggestion pour un événement.

```http
POST /api/contribution/suggestion/{id}
```

**Requête :**
- `id` (paramètre d'URL) : ID de l'événement.

**Corps de la requête :**
```json
{
    "contributions":"ici"
}
```

**Réponse :**
- Statut HTTP : 200 OK en cas de succès.
- Réponse JSON contenant la contribution créée.

### Marquer la contribution comme soutenue

Marquez une contribution comme soutenue par l'utilisateur actuel.

```http
GET /api/contribution/supported/{id}
```

**Requête :**
- `id` (paramètre d'URL) : ID de la contribution.

**Réponse :**
- Statut HTTP : 200 OK en cas de succès.
- Réponse JSON contenant la contribution mise à jour.

### Obtenir toutes les contributions pour un événement

Récupérez toutes les contributions pour un événement spécifique.

```http
GET /api/contribution/event/{id}
```

**Requête :**
- `id` (paramètre d'URL) : ID de l'événement.

**Réponse :**
- Statut HTTP : 200 OK en cas de succès.
- Réponse JSON contenant toutes les contributions pour l'événement spécifié.

### Supprimer une contribution

Supprimez une contribution (réservé à l'auteur).

```http
DELETE /api/contribution/delete/{id}
```

**Requête :**
- `id` (paramètre d'URL) : ID de la contribution.

**Réponse :**
- Statut HTTP : 200 OK en cas de succès.
- Réponse JSON indiquant la suppression réussie de la contribution.

### Marquer la contribution comme mon soutien

Marquez une contribution comme soutenue par l'utilisateur actuel pour un événement spécifique.

```http
GET /api/contribution/{id}/mySupported
```

**Requête :**
- `id` (paramètre d'URL) : ID de l'événement.`

**Réponse :**
- Statut HTTP : 200 OK en cas de succès.
- Réponse JSON contenant la contribution créée.

## Remarque

Assurez-vous de remplacer les espaces réservés tels que `{id}` et de mettre à jour la structure du corps de la requête JSON en fonction des besoins de votre application.
```

N'hésitez pas à personnaliser le modèle en fonction de votre cas d'utilisation spécifique et à ajouter toute information supplémentaire que vous estimez utile pour les utilisateurs interagissant avec votre API.
