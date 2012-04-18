--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: madefor; Type: TABLE; Schema: public; Owner: addy; Tablespace: 
--

CREATE TABLE madefor (
    mid integer NOT NULL,
    purch_id integer,
    prod_id integer,
    qnty numeric
);


ALTER TABLE public.madefor OWNER TO addy;

--
-- Name: madefor_mid_seq; Type: SEQUENCE; Schema: public; Owner: addy
--

CREATE SEQUENCE madefor_mid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.madefor_mid_seq OWNER TO addy;

--
-- Name: madefor_mid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: addy
--

ALTER SEQUENCE madefor_mid_seq OWNED BY madefor.mid;


--
-- Name: madefor_mid_seq; Type: SEQUENCE SET; Schema: public; Owner: addy
--

SELECT pg_catalog.setval('madefor_mid_seq', 1, false);


--
-- Name: product; Type: TABLE; Schema: public; Owner: addy; Tablespace: 
--

CREATE TABLE product (
    prodid integer NOT NULL,
    prodname character varying(30),
    price real,
    description character varying(500),
    qty numeric,
    date timestamp without time zone
);


ALTER TABLE public.product OWNER TO addy;

--
-- Name: product_prodid_seq; Type: SEQUENCE; Schema: public; Owner: addy
--

CREATE SEQUENCE product_prodid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_prodid_seq OWNER TO addy;

--
-- Name: product_prodid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: addy
--

ALTER SEQUENCE product_prodid_seq OWNED BY product.prodid;


--
-- Name: product_prodid_seq; Type: SEQUENCE SET; Schema: public; Owner: addy
--

SELECT pg_catalog.setval('product_prodid_seq', 1, true);


--
-- Name: purchase; Type: TABLE; Schema: public; Owner: addy; Tablespace: 
--

CREATE TABLE purchase (
    purchid integer NOT NULL,
    timestmp timestamp without time zone,
    total real,
    user_id integer
);


ALTER TABLE public.purchase OWNER TO addy;

--
-- Name: purchase_purchid_seq; Type: SEQUENCE; Schema: public; Owner: addy
--

CREATE SEQUENCE purchase_purchid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.purchase_purchid_seq OWNER TO addy;

--
-- Name: purchase_purchid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: addy
--

ALTER SEQUENCE purchase_purchid_seq OWNED BY purchase.purchid;


--
-- Name: purchase_purchid_seq; Type: SEQUENCE SET; Schema: public; Owner: addy
--

SELECT pg_catalog.setval('purchase_purchid_seq', 1, false);


--
-- Name: users; Type: TABLE; Schema: public; Owner: addy; Tablespace: 
--

CREATE TABLE users (
    userid integer NOT NULL,
    fname character varying(30),
    lname character varying(30),
    phone_no numeric,
    email_id character varying(30),
    password character varying(300),
    regdate date,
    perm character varying(20)
);


ALTER TABLE public.users OWNER TO addy;

--
-- Name: users_userid_seq; Type: SEQUENCE; Schema: public; Owner: addy
--

CREATE SEQUENCE users_userid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_userid_seq OWNER TO addy;

--
-- Name: users_userid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: addy
--

ALTER SEQUENCE users_userid_seq OWNED BY users.userid;


--
-- Name: users_userid_seq; Type: SEQUENCE SET; Schema: public; Owner: addy
--

SELECT pg_catalog.setval('users_userid_seq', 1, true);


--
-- Name: mid; Type: DEFAULT; Schema: public; Owner: addy
--

ALTER TABLE ONLY madefor ALTER COLUMN mid SET DEFAULT nextval('madefor_mid_seq'::regclass);


--
-- Name: prodid; Type: DEFAULT; Schema: public; Owner: addy
--

ALTER TABLE ONLY product ALTER COLUMN prodid SET DEFAULT nextval('product_prodid_seq'::regclass);


--
-- Name: purchid; Type: DEFAULT; Schema: public; Owner: addy
--

ALTER TABLE ONLY purchase ALTER COLUMN purchid SET DEFAULT nextval('purchase_purchid_seq'::regclass);


--
-- Name: userid; Type: DEFAULT; Schema: public; Owner: addy
--

ALTER TABLE ONLY users ALTER COLUMN userid SET DEFAULT nextval('users_userid_seq'::regclass);


--
-- Data for Name: madefor; Type: TABLE DATA; Schema: public; Owner: addy
--



--
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: addy
--



--
-- Data for Name: purchase; Type: TABLE DATA; Schema: public; Owner: addy
--



--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: addy
--

INSERT INTO users VALUES (1, 'Addy', 'Singh', 8888888888, 'addy689@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '2012-04-18', 'admin');


--
-- Name: madefor_pkey; Type: CONSTRAINT; Schema: public; Owner: addy; Tablespace: 
--

ALTER TABLE ONLY madefor
    ADD CONSTRAINT madefor_pkey PRIMARY KEY (mid);


--
-- Name: product_pkey; Type: CONSTRAINT; Schema: public; Owner: addy; Tablespace: 
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (prodid);


--
-- Name: purchase_pkey; Type: CONSTRAINT; Schema: public; Owner: addy; Tablespace: 
--

ALTER TABLE ONLY purchase
    ADD CONSTRAINT purchase_pkey PRIMARY KEY (purchid);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: addy; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (userid);


--
-- Name: UserId; Type: FK CONSTRAINT; Schema: public; Owner: addy
--

ALTER TABLE ONLY purchase
    ADD CONSTRAINT "UserId" FOREIGN KEY (user_id) REFERENCES users(userid);


--
-- Name: madefor_prod_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: addy
--

ALTER TABLE ONLY madefor
    ADD CONSTRAINT madefor_prod_id_fkey FOREIGN KEY (prod_id) REFERENCES product(prodid);


--
-- Name: madefor_purch_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: addy
--

ALTER TABLE ONLY madefor
    ADD CONSTRAINT madefor_purch_id_fkey FOREIGN KEY (purch_id) REFERENCES purchase(purchid);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

