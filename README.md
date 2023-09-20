
# Routes


## User
#### Login

```http
  POST /api/login
```
## Candidatures

#### Get all applications
```http
  GET /api/applications
```

#### Get one application
```http
  GET /api/applications/${id}
```
#### Apply
```http
  POST /api/apply/${campaign_id}
```
#### Delete application
```http
  DELETE /api/applications/${id}
```
#### Update application
```http
  PUT /api/updateApplication/${id}
```


## Campagnes

#### Get all items

```http
  GET /api/campaigns
```


#### Get item

```http
  GET /api/campaigns/${id}
```

#### Get all items belongs to one user

```http
  GET /api/my-campaigns
```

#### Create

```http
  POST /api/campaigns
```
#### Update

```http
  POST /api/updateCampaign/${id}
```

#### Delete

```http
  POST /api/campaigns/${id}
```
