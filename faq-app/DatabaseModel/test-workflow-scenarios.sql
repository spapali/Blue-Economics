-- pick a sample job
select * from blueeconomics.jobs where SocCode = '43-4071';

-- create an expert group
insert into blueecon_faq.expert_group(group_name, job_id) values ('43-4071 Experts', '43-4071');
select * from blueecon_faq.expert_group;

-- create an expert
insert into blueecon_faq.experts(username, first_name, organization, password) values( 'sujithpapali@yahoo.com', 'Sujith', 'BlueEcon', md5('password'));
select * from blueecon_faq.experts;

-- add expert to group
describe blueecon_faq.expert_group_members;
insert into blueecon_faq.expert_group_members(expert,`group`) values( 'sujithpapali@yahoo.com', '43-4071 Experts');
select * from blueecon_faq.expert_group_members;

-- user asks question by selecting job and entering email address
insert into blueecon_faq.questions(submitter,question,job_id) values ( 'obraafo@asemsebe.com', 'who is the master?', '43-4071' );
select * from blueecon_faq.questions;

-- find experts eligible to answer this question
select q.id, e.expert
from 
	blueecon_faq.questions q, 
	blueecon_faq.expert_group_members e, 
	blueecon_faq.expert_group g
where q.job_id = g.job_id
and e.`group` = g.group_name;

-- create workflow state for experts
insert into blueecon_faq.expert_question_state(username,question_id)
select e.expert, q.id
from 
	blueecon_faq.questions q, 
	blueecon_faq.expert_group_members e, 
	blueecon_faq.expert_group g
where q.job_id = g.job_id
and e.`group` = g.group_name;

select * from blueecon_faq.expert_question_state;

-- find all unanswered questions for a given expert
select s.*
from 
	blueecon_faq.expert_question_state s
where username = 'sujithpapali@yahoo.com'
and responded = false -- change to true for answered questions
and deleted = false;

-- mark the question as read
update blueecon_faq.expert_question_state set `read` = true
where question_id = 1;

-- respond to question
insert into blueecon_faq.responses(expert, question_id, response) values('sujithpapali@yahoo.com', 1, 'I am the master');
update blueecon_faq.user_question_sate set responded = true;


-- view responses
select * from blueecon_faq.responses
where question_id = 1
order by votes desc, created  desc;


-- upvote a response
update blueecon_faq.responses set votes = votes + 1
where id = 1;
/* ordering should change now */

