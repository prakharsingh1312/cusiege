<?php
session_start();
include('dbconfig.php');
function get_question(){
	global $dbconfig;
	$id=$_SESSION['i'];
	$qid=$_SESSION['r'][$id];
	echo $qid;
	$query=mysqli_query($dbconfig,"SELECT * FROM pre_lev1 where questionid=$qid");
	$res=mysqli_fetch_array($query);

			echo'<div class="question_div" id="question_div:'.$res['questionid'].'"><div class="question_inner_div">Question:<br> '.$res['question'].'</div><div id="marks_div'.$res['questionid'].'"class="marks_div">Marks:'.$_SESSION['score'].'</div></div><div class="option_div" id="option_div'.$res['questionid'].'">';
			
	
	
	echo "
	<h3>Options</h3>";
  					for($op=1;$op<5;$op++)
    				{
						echo '<div class="radiobtn"><input type="radio" name="'.$res['questionid'].'" class="quesradio'.$res['questionid'].'" id="'.$res['questionid'].'-'.$op.'" value="'.$res['choice'.$op].'"><label for="'.$res['questionid'].'-'.$op.'">'.$res['choice'.$op].'</label></div>';
						echo '<br> <br>';
						
					}
			echo '<input type="submit" value="Save Answer" class="save_answer_button button" id="save_answer:'.$res['questionid'].'"><br><br>
			</div>';
}

if(isset($_GET['submit'])){
	$qid=$_SESSION['r'][$_SESSION['i']];
	$answer=mysqli_real_escape_string($dbconfig,$_POST['answer']);
	$query=mysqli_query($dbconfig, "SELECT * from pre_lev1 where questionid=$qid");
	$res=mysqli_fetch_array($query);
	if($res['choice'.$res['answer']]!=$answer){
		$query=mysqli_query($dbconfig,"UPDATE login SET score_2=score_2-5");
		
		$_SESSION['score']-=5;
	}
	else{
		$query=mysqli_query($dbconfig,"UPDATE login SET score=score+{$_SESSION['score']}");
	}
	$query=mysqli_query($dbconfig,"insert into answers (userid,qid,answer) VALUES ({$_SESSION['userid']},$qid,'$answer')");
	$_SESSION['i']++;
	echo get_question();		
	}

else{
	$query=mysqli_query($dbconfig,"SELECT * FROM pre_lev1");
	if(mysqli_num_rows($query)==0)
		echo 1;
	else{
		$rows=mysqli_num_rows($query);
		$r=array();
		for($i=1;$i<=$rows;$i++){
			$r[$i]=$i;
		}
		shuffle($r);
		$_SESSION['r']=$r;
		$_SESSION['i']=1;
		$_SESSION['score']=100;
		echo get_question();
	}
}
?>