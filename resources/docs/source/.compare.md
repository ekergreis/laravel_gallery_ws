---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#general


<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Connexion utilisateur

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"username":"necessitatibus","password":"id","grant_type":"amet","client_id":"2","client_secret":"Hs9Jmsx0HDeOE4p9cHNefrLRlZI4vSgrdnjWlDgk","scope":"neque"}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "username": "necessitatibus",
    "password": "id",
    "grant_type": "amet",
    "client_id": "2",
    "client_secret": "Hs9Jmsx0HDeOE4p9cHNefrLRlZI4vSgrdnjWlDgk",
    "scope": "neque"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `username` | string |  required  | E-mail de connexion
        `password` | string |  required  | Mot de passe de l'utilisateur
        `grant_type` | string |  required  | Type de connexion valeur = password
        `client_id` | string |  required  | Identifiant client fourni par Laravel Passport.
        `client_secret` | string |  required  | Clé client fourni par Laravel Passport.
        `scope` | string |  required  | Privilège demandé valeur = *
    
<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_e0a641c96191824651592e4d848d5068 -->
## Infos galeries
Retourne pour l&#039;utilisateur connecté
les informations des galeries, groupes, podium best of images

> Example request:

```bash
curl -X GET \
    -G "http://www.laravel_gallery.local/api/galeries" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/galeries"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/galeries`


<!-- END_e0a641c96191824651592e4d848d5068 -->

<!-- START_204613676cab89a55dfdc7d81f16a281 -->
## Infos images
Liste des images accessibles dans une galerie

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/images" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":11,"img":[{"id":17}]}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/images"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 11,
    "img": [
        {
            "id": 17
        }
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/images`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de la galerie. Requis si aucun id image
        `img.*.id` | integer |  required  | identifiants des images à afficher (pour best of)
    
<!-- END_204613676cab89a55dfdc7d81f16a281 -->

<!-- START_38702aa9c6f225b36ff53e89358992ea -->
## Infos commentaires
Les des commentaires renseignés pour une image

> Example request:

```bash
curl -X GET \
    -G "http://www.laravel_gallery.local/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":2}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 2
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/comments`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de l'image
    
<!-- END_38702aa9c6f225b36ff53e89358992ea -->

<!-- START_7eaf5f4930153c54f919812f213a994c -->
## Ajout galerie
Enregistre une nouvelle galerie liée à un ou des groupes

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/galeries" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"name":"Vacances","descript":"Nous sommes partis \u00e0 4...","date_start":"2019-07-01","date_end":"2019-07-20","group":[{"id":7}]}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/galeries"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "name": "Vacances",
    "descript": "Nous sommes partis \u00e0 4...",
    "date_start": "2019-07-01",
    "date_end": "2019-07-20",
    "group": [
        {
            "id": 7
        }
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/galeries`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Libellé de la galerie.
        `descript` | string |  optional  | Description de la galerie.
        `date_start` | date |  optional  | Date début.
        `date_end` | date |  optional  | Date fin.
        `group.*.id` | integer |  required  | Identifiants des groupes autorisés à accéder à la galerie
    
<!-- END_7eaf5f4930153c54f919812f213a994c -->

<!-- START_10ec29912adcd2d1dca83504286a7769 -->
## Ajout image
Une image ne peut être ajoutée qu&#039;une fois dans une galerie (vérification doublon par checksum)

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/image_upload" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":2,"extension":"dolorem","data":"illum"}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/image_upload"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 2,
    "extension": "dolorem",
    "data": "illum"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/image_upload`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de la galerie
        `extension` | string |  required  | Extension du fichier image (limitée aux formats : jpg, jpeg, png)
        `data` | string |  required  | Image encodée en base64
    
<!-- END_10ec29912adcd2d1dca83504286a7769 -->

<!-- START_6c560cb463cae69ddba197afa896608b -->
## Ajout commentaire
Un commentaire ne peut pas être re-créé à l&#039;identique par le même utilisateur pour la même image

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":19,"comment":"J'aime beaucoup cette photo"}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 19,
    "comment": "J'aime beaucoup cette photo"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/comments`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de l'image
        `comment` | string |  required  | Texte de commentaire.
    
<!-- END_6c560cb463cae69ddba197afa896608b -->

<!-- START_56434bfd0ef62e3ac9e19ff33fa9cea3 -->
## Ajout like
L&#039;utilisateur indique qu&#039;il aime ou n&#039;aime plus une image

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/like" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id_image":16}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/like"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id_image": 16
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/like`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id_image` | integer |  required  | Identifiant de l'image (requis)
    
<!-- END_56434bfd0ef62e3ac9e19ff33fa9cea3 -->

<!-- START_d450468a10b3c3a70be5541f189d782d -->
## Suppression galerie
Seul un admin ou le créateur de la galerie peuvent supprimer une galerie

> Example request:

```bash
curl -X DELETE \
    "http://www.laravel_gallery.local/api/galeries" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":4}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/galeries"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 4
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/galeries`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de la galerie
    
<!-- END_d450468a10b3c3a70be5541f189d782d -->

<!-- START_c75b2cb29db1089e35d664b3e14b03ca -->
## Suppression image
Seul un admin ou celui qui a ajouté l&#039;image peuvent la supprimer

> Example request:

```bash
curl -X DELETE \
    "http://www.laravel_gallery.local/api/images" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":3}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/images"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 3
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/images`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant de l'image
    
<!-- END_c75b2cb29db1089e35d664b3e14b03ca -->

<!-- START_0925c150c097d5864398c78c161ac599 -->
## Suppression commentaire
Seul un admin ou celui qui a saisi le commentaire peuvent le supprimer

> Example request:

```bash
curl -X DELETE \
    "http://www.laravel_gallery.local/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":3}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 3
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/comments`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant du commentaire
    
<!-- END_0925c150c097d5864398c78c161ac599 -->

<!-- START_90f45d502fd52fdc0b289e55ba3c2ec6 -->
## Ajout utilisateur (admin)

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/signup" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"name":"libero","email":"a","password":"tempore","role":"ratione"}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/signup"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "name": "libero",
    "email": "a",
    "password": "tempore",
    "role": "ratione"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/signup`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Nom de l'utilisateur
        `email` | string |  required  | E-mail de l'utilisateur
        `password` | string |  required  | Mot de passe de l'utilisateur
        `role` | string |  required  | Mot de passe de l'utilisateur
    
<!-- END_90f45d502fd52fdc0b289e55ba3c2ec6 -->

<!-- START_007018a8a9f15c2d47fcb105431ffeee -->
## Infos user et groupes (admin)

> Example request:

```bash
curl -X GET \
    -G "http://www.laravel_gallery.local/api/groups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/groups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/groups`


<!-- END_007018a8a9f15c2d47fcb105431ffeee -->

<!-- START_15c22564ad248f952405021410fd1d25 -->
## Ajout groupe (admin)
Enregistre un nouveau groupe lié un ou des utilisateurs
Ou affecte un ou des utilisateurs à un groupe existant

> Example request:

```bash
curl -X POST \
    "http://www.laravel_gallery.local/api/groups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":"tenetur","name":"Famille","usergroup":[{"id":7}]}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/groups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": "tenetur",
    "name": "Famille",
    "usergroup": [
        {
            "id": 7
        }
    ]
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/groups`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | string |  required  | Id du groupe. Requis sans name
        `name` | string |  required  | Libellé du groupe. Requis sans Id.
        `usergroup.*.id` | integer |  optional  | Identifiants des utilisateurs autorisés à accéder au groupe
    
<!-- END_15c22564ad248f952405021410fd1d25 -->

<!-- START_797bc72d26115689bba2441f6d653d63 -->
## Suppression user d&#039;un groupe (admin)

> Example request:

```bash
curl -X DELETE \
    "http://www.laravel_gallery.local/api/groups_user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":8,"usergroup":[{"id":20}]}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/groups_user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 8,
    "usergroup": [
        {
            "id": 20
        }
    ]
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/groups_user`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant du groupe à supprimer.
        `usergroup.*.id` | integer |  optional  | Identifiants des utilisateurs à supprimer du groupe
    
<!-- END_797bc72d26115689bba2441f6d653d63 -->

<!-- START_bf71323292c0b1468b4e5e727c154962 -->
## Suppression groupe (admin)

> Example request:

```bash
curl -X DELETE \
    "http://www.laravel_gallery.local/api/groups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}" \
    -d '{"id":19}'

```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/groups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

let body = {
    "id": 19
}

fetch(url, {
    method: "DELETE",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/groups`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  required  | Identifiant du groupe à supprimer.
    
<!-- END_bf71323292c0b1468b4e5e727c154962 -->

<!-- START_00e7e21641f05de650dbe13f242c6f2c -->
## Déconnexion utilisateur
Révocation du token

> Example request:

```bash
curl -X GET \
    -G "http://www.laravel_gallery.local/api/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "X-Requested-With: XMLHttpRequest" \
    -H "Authorization: Bearer {token}"
```

```javascript
const url = new URL(
    "http://www.laravel_gallery.local/api/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-Requested-With": "XMLHttpRequest",
    "Authorization": "Bearer {token}",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/logout`


<!-- END_00e7e21641f05de650dbe13f242c6f2c -->


