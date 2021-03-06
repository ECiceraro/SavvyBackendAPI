<?PHP

// require_once '../../vendor/autoload.php';

// $db = new PDO($dsn);
// $dbconn = pg_connect("$DBURL");
$dbconn = pg_connect(getenv("DATABASE_URL"));
// (id SERIAL, restid int, author varchar(30), comment varchar(140), password varchar(8), date timestamp DEFAULT now())

// $db = parse_url(getenv("DATABASE_URL"));
//
// $dbconn = new PDO("pgsql:" . sprintf(
//     "host=%s;port=%s;user=%s;password=%s;dbname=%s",
//     $db["host"],
//     $db["port"],
//     $db["user"],
//     $db["pass"],
//     ltrim($db["path"], "/")
// ));

class Comment {
    public $id;
    public $restid;
    public $author;
    public $comment;
    public $password;
    public $date;

    public function __construct($id, $restid, $author, $comment, $password, $date) {
        $this->id = $id;
        $this->restid = $restid;
        $this->author = $author;
        $this->comment = $comment;
        $this->password = $password;
        $this->date = $date;
    }
}

class Comments {



    static function all(){
        //create an empty array
        $comments = array();

        //query the database
        $results = pg_query("SELECT * FROM comments");

        $row_object = pg_fetch_object($results);
        // var_dump($row_object);
        while($row_object){

                    $new_comment = new Comment(
                        intval($row_object->id),
                        intval($row_object->restid),
                        $row_object->author,
                        $row_object->comment,
                        $row_object->password,
                        $row_object->date

                    );
                    $comments[] = $new_comment;

                    // print_r($comments);
                    $row_object = pg_fetch_object($results);
                }

        // die(); //halt execution

        // print_r($comments);
        return $comments;
    }


    static function some($restid){
        //create an empty array
        $comments = array();

        //query the database
        $results = pg_query("SELECT * FROM comments WHERE restid=$restid;");

        $row_object = pg_fetch_object($results);
        // var_dump($row_object);
        while($row_object){

                    $new_comment = new Comment(
                        intval($row_object->id),
                        intval($row_object->restid),
                        $row_object->author,
                        $row_object->comment,
                        $row_object->password,
                        $row_object->date

                    );
                    $comments[] = $new_comment;

                    // print_r($comments);
                    $row_object = pg_fetch_object($results);
                }

        // die(); //halt execution

        // print_r($comments);
        return $comments;
    }


    static function create($comment){
        $query = "INSERT INTO comments (restid, author, comment, password) VALUES ($1, $2, $3, $4)";
        $query_params = array($comment->restid, $comment->author, $comment->comment, $comment->password);
        pg_query_params($query, $query_params);
        return self::all();
    }

    static function update($updated_comment){
        $query = "UPDATE comments SET restid = $1, author = $2, comment = $3, password=$4, date = $5 WHERE id = $6";
        $query_params = array($updated_comment->restid, $updated_comment->author, $updated_comment->comment, $updated_comment->password,$updated_comment->date,$updated_comment->id);
        $result = pg_query_params($query, $query_params);

        return self::all();
    }

    static function delete($id){
    $query = "DELETE FROM comments WHERE id = $1";
    $query_params = array($id);
    $result = pg_query_params($query, $query_params);

    return self::all();
    }



}


?>
