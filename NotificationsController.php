<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifications;

//newly added
use DB;

use App\Models\post;
use App\Models\Mastercountry;
use App\Models\MasterState;


class NotificationsController extends Controller
{

    public function Notifications_index(Request $request){   
            //->where('updated_at',"2021-12-09 14:20:17") index temp shld be removed
        //$notifications = notifications::all()->where('forum_group_id',$request->forum_group_id);
        /*return response()->json([
                'status' => 200,
                'message' => $notifications,
        ]);*/

    }


//view notifications when notify true show total count likes+comments 
public function Notifications_viewbadgescount(Request $request){   
       echo "POSTWISE LIKES COMMENTS:-";
       //SELECT post.* from post,likes where post.id=likes.post_id group by post.id
       
       $v1= "VIEW NOTIFICATIONS LIKES BADGES COUNT~~~~~";            
       $notificationslikescount=Notifications::join('likes','likes.post_id', '=','post.id' )
                           //->distinct()->first()
                           ->get(['post.id','post.likes_count']);
       
        //return $notificationslikescount;                   
              
       $v2= "VIEW NOTIFICATIONS COMMENTS BADGES COUNT~~~~~";            
       
       $notificationscommentscount=Notifications::join('comments_notify','comments_notify.post_id' , '=', 'post.id')
                            //->distinct()->first()
                            ->get(['post.id','post.comments_count']);
        
   
        print_r($v1);
                 return response()->json([
                'status' => 200,
                'message' => $v1,$notificationslikescount,$v2,$notificationscommentscount ]);

    }


//notifications show notify update
    public function Notifications_showlistupdatenotify(){
        echo "Show List";
        //country name get from country master
        //state name get from state master      
        
        $notificationslist=Notifications::
            join('likes', 'likes.post_id', '=','post.id')
          ->join('comments_notify','comments_notify.post_id', '=','post.id')

          ->join('master_country', 'master_country.id', '=', 'likes.country_id')
          ->join('master_state', 'master_state.id', '=', 'likes.state_id')

          //->join('master_country', 'master_country.id', '=', 'comments_notify.country_id')
          //->join('master_state', 'master_state.id', '=', 'comments_notify.state_id')
          
          //->join('users','users.user_id', '=','post.id')
          ->join('forum','forum.id', '=','post.forum_id')
        //->join('forumgroup','forumgroup.id', '=','post.id')
          ->where([['likes.notify','=',1],['comments_notify.notify','=',1]])
          ->get(['likes.id','likes.user_id','likes.role','likes.name','likes.title','likes.country_id','master_country.country_name','likes.state_id','master_state.state_name',
            'comments_notify.id','comments_notify.user_id','comments_notify.role','comments_notify.name','comments_notify.title','comments_notify.country_id','master_country.country_name','comments_notify.state_id','master_state.state_name' ])           ;

        

    //get store user_id  by above query r userlogin where clauses

      //updating notify = 0 as seen
       //where clause user_id //  
      $dt=date("Y-m-d H:i:s");
      DB::update("update likes INNER JOIN post ON likes.post_id = post.id SET likes.notify=false,lastnotified_dt='$dt' WHERE likes.post_id = post.id");
      DB::update("update comments_notify INNER JOIN post ON comments_notify.post_id = post.id SET comments_notify.notify=false,lastnotified_dt='$dt' WHERE comments_notify.post_id = post.id");

      //print_r($updatenotify);
      return response()->json([
                    'status' => 200,
                    'message' => 'Updated notify seen all Successfull',$notificationslist
                ]);         

   }


    
}
