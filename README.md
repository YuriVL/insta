## Quickstart
1. [Install git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
2. [Install docker](https://docs.docker.com/install/)
3. [Install docker-compose](https://docs.docker.com/compose/install/)
4. Run
```
git clone https://github.com/YuriVL/insta.git
cd test
cp .env.docker .env
docker-compose up -d --build

docker-compose exec app bash
composer install
```
Add this lines to your hosts file
```
127.0.0.1 gk.lcl
127.0.0.1 admin.gk.lcl
```
Go to [http://gk.lcl:8089](http://admin.gk.lcl:8089)

Add file niknames.json to the root directory.
For instanse
```
{
  "1": "iphotels",
  "2": "bogdee",
  "3": "nikolaibaskov"
}
```